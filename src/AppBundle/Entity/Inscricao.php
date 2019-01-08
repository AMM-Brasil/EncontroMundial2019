<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Inscricao
{
    /** @ORM\Column @ORM\Id */
    private $id;

    /** @ORM\Column */
    private $cidade;

    /** @ORM\Column */
    private $uf;

    /** @ORM\Column */
    private $diretor;

    /** @ORM\Column */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="Membro", mappedBy="inscricao")
     * @ORM\OrderBy({"nome" = "ASC"})
     */
    private $membros;

    /**
     * @ORM\Column(type="boolean")
     */
    private $depositoIdentificado = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $venda = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $custoInscricao = 100;

    /**
     * @ORM\Column(type="integer")
     */
    private $custoCamiseta = 30;

    public function __construct()
    {
        $this->membros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->id = strtoupper(substr(uniqid(), -6, 6));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function getUf()
    {
        return $this->uf;
    }

    public function getDiretor()
    {
        return $this->diretor;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    public function setDiretor($diretor)
    {
        $this->diretor = $diretor;

        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getMembros()
    {
        return $this->membros;
    }

    public function setMembros($membros)
    {
        $this->membros = $membros;

        return $this;
    }

    public function getDepositoIdentificado()
    {
        return $this->depositoIdentificado;
    }

    public function setDepositoIdentificado($depositoIdentificado)
    {
        $this->depositoIdentificado = $depositoIdentificado;

        return $this;
    }

    /**
     * Get the value of custoInscricao.
     */
    public function getCustoInscricao()
    {
        return $this->custoInscricao;
    }

    /**
     * Set the value of custoInscricao.
     *
     * @return self
     */
    public function setCustoInscricao($custoInscricao)
    {
        $this->custoInscricao = $custoInscricao;

        return $this;
    }

    /**
     * Get the value of custoCamiseta.
     */
    public function getCustoCamiseta()
    {
        return $this->custoCamiseta;
    }

    /**
     * Set the value of custoCamiseta.
     *
     * @return self
     */
    public function setCustoCamiseta($custoCamiseta)
    {
        $this->custoCamiseta = $custoCamiseta;

        return $this;
    }

    public function getCustoTotal()
    {
        return $this->getCustoInscricao() * $this->getMembros()->count();
    }

    /**
     * Get the value of venda.
     */
    public function getVenda()
    {
        return $this->venda;
    }

    /**
     * Set the value of venda.
     *
     * @return self
     */
    public function setVenda($venda)
    {
        $this->venda = $venda;

        return $this;
    }
}
