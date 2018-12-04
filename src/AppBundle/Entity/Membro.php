<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\MembroRepository")
 */
class Membro
{
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
     * @ORM\Column(type="boolean")
     */
    private $passeio = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $restaurante = true;

    /**
     * @ORM\ManyToOne(targetEntity="Inscricao", inversedBy="membros")
     * @ORM\JoinColumn(name="inscricao_id", referencedColumnName="id")
     */
    private $inscricao;

    public function __construct()
    {
        $this->nome = 'NOVO MEMBRO';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getVeiculo()
    {
        return $this->veiculo;
    }

    public function getEstadia()
    {
        return $this->estadia;
    }

    public function getCamiseta()
    {
        return $this->camiseta;
    }

    public function getCalcado()
    {
        return $this->calcado;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    public function setVeiculo($veiculo)
    {
        $this->veiculo = $veiculo;

        return $this;
    }

    public function setEstadia($estadia)
    {
        $this->estadia = $estadia;

        return $this;
    }

    public function setCamiseta($camiseta)
    {
        $this->camiseta = $camiseta;

        return $this;
    }

    public function setCalcado($calcado)
    {
        $this->calcado = $calcado;

        return $this;
    }

    public function getInscricao()
    {
        return $this->inscricao;
    }

    public function setInscricao($inscricao)
    {
        $this->inscricao = $inscricao;

        return $this;
    }

    /**
     * Get the value of passeio.
     */
    public function getPasseio()
    {
        return $this->passeio;
    }

    /**
     * Set the value of passeio.
     *
     * @return self
     */
    public function setPasseio($passeio)
    {
        $this->passeio = $passeio;

        return $this;
    }

    /**
     * Get the value of restaurante.
     */
    public function getRestaurante()
    {
        return $this->restaurante;
    }

    /**
     * Set the value of restaurante.
     *
     * @return self
     */
    public function setRestaurante($restaurante)
    {
        $this->restaurante = $restaurante;

        return $this;
    }
}
