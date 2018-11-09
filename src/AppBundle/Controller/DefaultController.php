<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Inscricao;
use AppBundle\Entity\Membro;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/edit-step-1/{id}", name="edit-step-1")
     */
    public function editStep1($id = null) {
        do {
            $inscricao = is_null($id) ?
                    new Inscricao() :
                    $this->getDoctrine()->getRepository(Inscricao::class)->find($id);
        } while (is_null($id) && !is_null($this->getDoctrine()->getRepository(Inscricao::class)->find($inscricao->getId())));
        if (is_null($inscricao)) {
            return $this->render('default/fail.html.twig', [
                        'id' => $id
            ]);
        } else {
            return $this->render("default/edit.step1.html.twig", [
                        'inscricao' => $inscricao
            ]);
        }
    }

    /**
     * @Route("/edit-step-1/{id}/do", name="do-edit-step-1")
     */
    public function doEditStep1Action(Request $request, $id) {
        $inscricao = $this->getDoctrine()->getRepository(Inscricao::class)->find($id);
        if (is_null($inscricao)) {
            $inscricao = new Inscricao();
            $inscricao->setId($id);
        }
        $inscricao
                ->setCidade($request->get('cidade'))
                ->setUf($request->get('uf'))
                ->setDiretor($request->get('diretor'))
                ->setEmail($request->get('email'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($inscricao);
        $em->flush();
        return $this->redirectToRoute('edit-step-2', ['inscricao' => $id]);
    }

    /**
     * @Route("/edit-step-2/{inscricao}", name="edit-step-2")
     */
    public function editStep2(Inscricao $inscricao) {
        $catreBlocoA = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-A');
        $catreBlocoB = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-B');
        return $this->render("default/edit.step2.html.twig", [
                    'inscricao' => $inscricao,
                    'catreBlocoA' => $catreBlocoA,
                    'catreBlocoB' => $catreBlocoB
        ]);
    }

    /**
     * @Route("/edit-step-2/{inscricao}/do", name="do-edit-step-2")
     */
    public function doEditStep2Action(Request $request, Inscricao $inscricao) {
        $this->saveMembros($request, $inscricao);
        return $this->redirectToRoute('edit-step-3', ['inscricao' => $inscricao->getId()]);
    }
    
    public function saveMembros(Request $request, Inscricao $inscricao) {
        $em = $this->getDoctrine()->getManager();
        foreach ($request->get('membro') as $membro) {
            $bMembro = empty($membro['id']) ? new Membro() : $this->getDoctrine()->getRepository(Membro::class)->find($membro['id']);
            $bMembro
                    ->setNome($membro['nome'])
                    ->setVeiculo($membro['veiculo'])
                    ->setEstadia($membro['estadia'])
                    ->setCamiseta($membro['camiseta'])
                    ->setCalcado($membro['calcado'])
                    ->setInscricao($inscricao);
            $em->persist($bMembro);
        }
        $em->flush();
    }

    /**
     * @Route("/edit-step-3/{inscricao}", name="edit-step-3")
     */
    public function editStep3(Inscricao $inscricao) {
        return $this->render("default/edit.step3.html.twig", [
                    'inscricao' => $inscricao
        ]);
    }

    /**
     * @Route("/edit-step-3/{inscricao}/do", name="do-edit-step-3")
     */
    public function doEditStep3Action(Request $request, Inscricao $inscricao) {
        $inscricao
                ->setCamisetasAvulsasG($request->get('G'))
                ->setCamisetasAvulsasGG($request->get('GG'))
                ->setCamisetasAvulsasInfantilG($request->get('infantilG'))
                ->setCamisetasAvulsasInfantilM($request->get('infantilM'))
                ->setCamisetasAvulsasInfantilP($request->get('infantilP'))
                ->setCamisetasAvulsasM($request->get('M'))
                ->setCamisetasAvulsasP($request->get('P'))
                ->setCamisetasAvulsasXG($request->get('XG'))
                ->setCamisetasAvulsasXXG($request->get('XXG'))
                ->setCamisetasAvulsasXXXG($request->get('XXXG'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($inscricao);
        $em->flush();
        return $this->redirectToRoute('resumo-inscricao', ['inscricao' => $inscricao->getId()]);
    }

    /**
     * @Route("/resumo/{inscricao}", name="resumo-inscricao")
     */
    public function resumoInscricao(Inscricao $inscricao) {
        $custoInscricao = 100;
        $custoCamiseta = 30;
        $totalInscricoes = $inscricao->getMembros()->count() * $custoInscricao;
        $camisetasAvulsas = $inscricao->getCamisetasAvulsasG() +
                $inscricao->getCamisetasAvulsasGG() +
                $inscricao->getCamisetasAvulsasInfantilG() +
                $inscricao->getCamisetasAvulsasInfantilM() +
                $inscricao->getCamisetasAvulsasInfantilP() +
                $inscricao->getCamisetasAvulsasM() +
                $inscricao->getCamisetasAvulsasP() +
                $inscricao->getCamisetasAvulsasXG() +
                $inscricao->getCamisetasAvulsasXXG() +
                $inscricao->getCamisetasAvulsasXXXG();
        $totalCamisetas = $camisetasAvulsas * $custoCamiseta;
        return $this->render('default/resumo.html.twig', [
                    'custoInscricao' => $custoInscricao,
                    'custoCamiseta' => $custoCamiseta,
                    'totalInscricoes' => $totalInscricoes,
                    'totalCamisetas' => $totalCamisetas,
                    'camisetasAvulsas' => $camisetasAvulsas,
                    'inscricao' => $inscricao
        ]);
    }

    /**
     * @Route("/edit-select", name="edit-select")
     */
    public function editSelect(Request $request) {
        $err = false;
        if ($request->get('id')) {
            if ($this->getDoctrine()->getRepository(Inscricao::class)->find($request->get('id'))) {
                return $this->redirectToRoute('edit-step-1', ['id' => $request->get('id')]);
            }
            $err = true;
        }
        return $this->render('default/select.html.twig', [
                    'err' => $err
        ]);
    }

    /**
     * @Route("/addMembro/{inscricao}", name="adicionar-membro")
     */
    public function addMembro(Request $request, Inscricao $inscricao) {
        $this->saveMembros($request, $inscricao);
        $catreBlocoA = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-A');
        $catreBlocoB = $this->getDoctrine()->getRepository(Membro::class)->countByCatreBloco('CATRE-B');
        return $this->render('default/membro.html.twig', [
                    'membro' => new Membro(),
                    'index' => $request->get('currentIndex'),
                    'catreBlocoA' => $catreBlocoA,
                    'catreBlocoB' => $catreBlocoB
        ]);
    }

    /**
     * @Route("/delMembro", name="remover-membro")
     */
    public function delMembro(Request $request) {
        $membro = $this->getDoctrine()->getRepository(Membro::class)->find($request->get('id', 0));
        if (!is_null($membro)) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($membro);
            $em->flush();
        }
        return new \Symfony\Component\HttpFoundation\Response();
    }

}
