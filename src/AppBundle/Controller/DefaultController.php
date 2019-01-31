<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Inscricao;
use AppBundle\Entity\Membro;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Comprovante;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/edit-step-1/{id}", name="edit-step-1")
     */
    public function editStep1($id = null)
    {
        do {
            $inscricao = is_null($id) ?
                new Inscricao() :
                $this->getDoctrine()->getRepository(Inscricao::class)->find($id);
        } while (is_null($id) && !is_null($this->getDoctrine()->getRepository(Inscricao::class)->find($inscricao->getId())));
        if (is_null($inscricao)) {
            return $this->render('default/fail.html.twig', [
                'id' => $id,
            ]);
        } else {
            return $this->render('default/edit.step1.html.twig', [
                'inscricao' => $inscricao,
            ]);
        }
    }

    /**
     * @Route("/edit-step-1/{id}/do", name="do-edit-step-1")
     */
    public function doEditStep1Action(Request $request, $id)
    {
        $inscricao = $this->getDoctrine()->getRepository(Inscricao::class)->find($id);
        if (is_null($inscricao)) {
            $inscricao = new Inscricao();
            $inscricao->setId($id);
        }
        $inscricao
            ->setCidade($request->get('cidade'))
            ->setUf($request->get('uf'))
            ->setDiretor($request->get('diretor'))
            ->setEmail($request->get('email'))
            ->setVenda($request->get('venda'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($inscricao);
        $em->flush();

        return $this->redirectToRoute('edit-step-2', ['inscricao' => $id]);
    }

    /**
     * @Route("/edit-step-2/{inscricao}", name="edit-step-2")
     */
    public function editStep2(Inscricao $inscricao)
    {
        $catreBlocoA = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-A');
        $catreBlocoB = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-B');
        $catreBlocoC = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-B');

        return $this->render('default/edit.step2.html.twig', [
            'inscricao' => $inscricao,
            'catreBlocoA' => $catreBlocoA,
            'catreBlocoB' => $catreBlocoB,
            'catreBlocoC' => $catreBlocoC,
        ]);
    }

    /**
     * @Route("/edit-step-2/{inscricao}/do", name="do-edit-step-2")
     */
    public function doEditStep2Action(Request $request, \Swift_Mailer $mailer, Inscricao $inscricao)
    {
        $this->saveMembros($request, $inscricao);
        if (!$inscricao->getEmailEnviado()) {
            $url = $this->generateUrl('resumo-inscricao', ['inscricao' => $inscricao->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $message = (new \Swift_Message('Inscrição no Encontro Mundial AMM'))
                ->setFrom(['comunicacao@amm-brasil.org' => 'Comunicação AMM'])
                ->setTo($inscricao->getEmail())
                ->setBody(
                    sprintf(
                        'A inscrição da sua Regional foi realizada com sucesso com o número: <b>%s</b><br>'.
                            'Realize o pagamento, insira o comprovante no sistema e aguarde a confirmação do tesoureiro. Você receberá um e-mail quando o depósito for confirmado.<br>'.
                            'Acompanhe o processo ou edite sua inscrição através do link: <a href="%s" target="_blank">%s</a>',
                        $inscricao->getId(),
                        $url,
                        $url
                    ),
                    'text/html'
                );
            $mailer->send($message);
            $inscricao->setEmailEnviado(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($inscricao);
            $em->flush();
        }

        return $this->redirectToRoute('edit-step-3', ['inscricao' => $inscricao->getId()]);
    }

    /**
     * @Route("/edit-step-3/{inscricao}", name="edit-step-3")
     */
    public function editStep3(Request $request, \Swift_Mailer $mailer, Inscricao $inscricao)
    {
        $comprovante = new Comprovante($inscricao);
        $form = $this->createFormBuilder($comprovante)
            ->add('arquivo', FileType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caminho = md5(uniqid()).'.'.$comprovante->getArquivo()->guessExtension();
            $comprovante->getArquivo()->move(
                $this->get('kernel')->getRootDir().'/../web/comprovantes',
                $caminho
            );
            $comprovante->setArquivo($caminho);
            $inscricao->setDepositoIdentificado(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($comprovante);
            $em->persist($inscricao);
            $em->flush();
            $url = $this->generateUrl('resumo-inscricao', ['inscricao' => $inscricao->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $urlAdmin = $this->generateUrl('admin', [], UrlGeneratorInterface::ABSOLUTE_URL);
            $mTes = (new \Swift_Message('Novo comprovante'))
                ->setFrom(['comunicacao@amm-brasil.org' => 'Comunicação AMM'])
                ->setTo('tesouraria@amm-brasil.org')
                ->setBody(
                    sprintf(
                        'O Diretor da Regional de %s - %s adicionou um novo comprovante de depósito no sistema.<br>'.
                            'Favor, verificar na conta do AMM e identificar no sistema.<br>'.
                            'Inscrição da Regional: <a href="%s" target="_blank">%s</a><br>'.
                            'Sistema administrativo: <a href="%s" target="_blank">%s</a>',
                        $inscricao->getCidade(),
                        $inscricao->getUf(),
                        $url,
                        $url,
                        $urlAdmin,
                        $urlAdmin
                    ),
                    'text/html'
                );
            $mailer->send($mTes);
        }

        return $this->render('default/edit.step3.html.twig', [
            'inscricao' => $inscricao,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/saveMembro/{inscricao}", name="save-membro")
     */
    public function saveMembros(Request $request, Inscricao $inscricao)
    {
        if ($request->get('membro')) {
            $em = $this->getDoctrine()->getManager();
            foreach ($request->get('membro') as $membro) {
                $bMembro = empty($membro['id']) ? new Membro() : $this->getDoctrine()->getRepository(Membro::class)->find($membro['id']);
                $bMembro
                    ->setNome($membro['nome'])
                    ->setVeiculo($membro['veiculo'])
                    ->setEstadia($membro['estadia'])
                    ->setCamiseta($membro['camiseta'])
                    ->setCalcado($membro['calcado'])
                    ->setInscricao($inscricao)
                    ->setPasseio($membro['passeio'])
                    ->setRestaurante($membro['restaurante']);
                $em->persist($bMembro);
            }
            $em->flush();
        }

        return $this->redirectToRoute('edit-step-2', ['inscricao' => $inscricao->getId()]);
    }

    /**
     * @Route("/resumo/{inscricao}", name="resumo-inscricao")
     */
    public function resumoInscricao(Request $request, Inscricao $inscricao)
    {
        return $this->render('default/resumo.html.twig', [
            'inscricao' => $inscricao,
            'form' => $form->createView(),
            'success' => $request->get('success'),
        ]);
    }

    /**
     * @Route("/edit-select", name="edit-select")
     */
    public function editSelect(Request $request)
    {
        $err = false;
        if ($request->get('id')) {
            if ($this->getDoctrine()->getRepository(Inscricao::class)->find($request->get('id'))) {
                return $this->redirectToRoute('edit-step-1', ['id' => $request->get('id')]);
            }
            $err = true;
        }

        return $this->render('default/select.html.twig', [
            'err' => $err,
        ]);
    }

    /**
     * @Route("/addMembro/{inscricao}", name="adicionar-membro")
     */
    public function addMembro(Request $request, Inscricao $inscricao)
    {
        $this->saveMembros($request, $inscricao);
        $inscricao->setMembros(new ArrayCollection(array_merge([new Membro()], $inscricao->getMembros()->toArray())));

        return $this->editStep2($inscricao);
    }

    /**
     * @Route("/delMembro", name="remover-membro")
     */
    public function delMembro(Request $request)
    {
        $membro = $this->getDoctrine()->getRepository(Membro::class)->find($request->get('id', 0));
        if (!is_null($membro)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membro);
            $em->flush();
        }

        return new \Symfony\Component\HttpFoundation\Response();
    }

    /**
     * @Route("/delComprovante", name="excluir-comprovante")
     */
    public function excluirComprovante(Request $request)
    {
        $comp = $this->getDoctrine()->getRepository(Comprovante::class)->find($request->get('id', 0));
        if (!is_null($comp)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comp);
            $em->flush();
        }

        return new \Symfony\Component\HttpFoundation\Response();
    }
}
