<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Inscricao;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction()
    {
        $inscricoes = $this->getDoctrine()->getRepository(Inscricao::class)->findAll();

        return $this->render('admin/index.html.twig', ['inscricoes' => $inscricoes]);
    }

    /**
     * @Route("/admin/deposito/{inscricao}", name="admin_deposito")
     */
    public function depositoAction(Request $request, Inscricao $inscricao)
    {
        $inscricao->setDepositoIdentificado($request->query->get('checked') == 'true');
        $enm = $this->getDoctrine()->getManager();
        $enm->persist($inscricao);
        $enm->flush();

        return new Response('ok');
    }

    /**
     * @Route("/admin/listaGeral", name="admin_geral")
     */
    public function listaGeral()
    {
        $inscricoes = $this->getDoctrine()->getRepository(Inscricao::class)->findAll();
        $quantidadeInscricoes = count($inscricoes);
        $inscricoesPagas = array_filter($inscricoes, function (Inscricao $inscricao) {
            return $inscricao->getDepositoIdentificado();
        });
        $quantidadeInscricoesPagas = count($inscricoesPagas);
        $veiculos = array();
        $estadias = array();
        $camisetas = array();
        $calcados = array();
        $vendas = array();
        $quantidadePasseio = 0;
        $quantidadeRestaurante = 0;
        foreach ($inscricoesPagas as $inscricao) {
            if ($inscricao->getVenda()) {
                array_push($vendas, $inscricao);
            }
            foreach ($inscricao->getMembros() as $membro) {
                if (!\array_key_exists($membro->getVeiculo(), $veiculos)) {
                    $veiculos[$membro->getVeiculo()] = 0;
                }
                if (!\array_key_exists($membro->getEstadia(), $estadias)) {
                    $estadias[$membro->getEstadia()] = 0;
                }
                if (!\array_key_exists($membro->getCamiseta(), $camisetas)) {
                    $camisetas[$membro->getCamiseta()] = 0;
                }
                if (!\array_key_exists($membro->getCalcado(), $calcados)) {
                    $calcados[$membro->getCalcado()] = 0;
                }
                if ($membro->getPasseio()) {
                    ++$quantidadePasseio;
                }
                if ($membro->getRestaurante()) {
                    ++$quantidadeRestaurante;
                }
                ++$veiculos[$membro->getVeiculo()];
                ++$estadias[$membro->getEstadia()];
                ++$camisetas[$membro->getCamiseta()];
                ++$calcados[$membro->getCalcado()];
            }
        }

        return $this->render('admin/listageral.html.twig', [
            'quantidadeInscricoes' => $quantidadeInscricoes,
            'quantidadeInscricoesPagas' => $quantidadeInscricoesPagas,
            'veiculos' => $veiculos,
            'estadias' => $estadias,
            'camisetas' => $camisetas,
            'calcados' => $calcados,
            'quantidadePasseio' => $quantidadePasseio,
            'quantidadeRestaurante' => $quantidadeRestaurante,
            'vendas' => $vendas,
        ]);
    }
}