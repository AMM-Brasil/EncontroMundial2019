<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\MembroRepository")
 */
class Membro {

    /** @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue(strategy="AUTO") */
    private $id;

    /** @ORM\Column */
    private $nome;

    /** @ORM\Column */
    private $veiculo;

    /** @ORM\Column */
    private $estadia;

    /** @ORM\Column */
    private $camiseta;

    /** @ORM\Column(type="integer") */
    private $calcado;

    /**
     * @ORM\ManyToOne(targetEntity="Inscricao", inversedBy="membros")
     * @ORM\JoinColumn(name="inscricao_id", referencedColumnName="id")
     */
    private $inscricao;

    public function __construct() {
        $this->nome = 'Novo membro';
    }

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getVeiculo() {
        return $this->veiculo;
    }

    function getEstadia() {
        return $this->estadia;
    }

    function getCamiseta() {
        return $this->camiseta;
    }

    function getCalcado() {
        return $this->calcado;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    function setVeiculo($veiculo) {
        $this->veiculo = $veiculo;
        return $this;
    }

    function setEstadia($estadia) {
        $this->estadia = $estadia;
        return $this;
    }

    function setCamiseta($camiseta) {
        $this->camiseta = $camiseta;
        return $this;
    }

    function setCalcado($calcado) {
        $this->calcado = $calcado;
        return $this;
    }

    function getInscricao() {
        return $this->inscricao;
    }

    function setInscricao($inscricao) {
        $this->inscricao = $inscricao;
        return $this;
    }

}
