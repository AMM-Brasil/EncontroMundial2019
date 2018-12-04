<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Inscricao;

class CatreController extends Controller
{
    /**
     * @Route("/catre", name="catre")
     */
    public function indexAction(Request $request)
    {
        $err = false;
        if ($request->get('id')) {
            if ($this->getDoctrine()->getRepository(Inscricao::class)->find($request->get('id'))) {
                return $this->redirectToRoute('catre_resumo', ['inscricao' => $request->get('id')]);
            }
            $err = true;
        }

        return $this->render('catre/select.html.twig', [
            'err' => $err,
        ]);
    }

    /**
     * @Route("/catre/resumo/{inscricao}", name="catre_resumo")
     */
    public function resumoInscricao(Inscricao $inscricao)
    {
        $estadias = array();
        foreach ($inscricao->getMembros() as $membro) {
            if (!\array_key_exists($membro->getEstadia(), $estadias)) {
                $estadias[$membro->getEstadia()] = 0;
            }
            ++$estadias[$membro->getEstadia()];
        }

        return $this->render('catre/resumo.html.twig', [
            'inscricao' => $inscricao,
            'estadias' => $estadias,
        ]);
    }
}
