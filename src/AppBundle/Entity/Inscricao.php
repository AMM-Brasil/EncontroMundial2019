<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Inscricao {

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

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasInfantilP = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasInfantilM = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasInfantilG = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasP = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasM = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasG = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasGG = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasXG = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasXXG = 0;

    /** @ORM\Column(type="integer") */
    private $camisetasAvulsasXXXG = 0;

    /**
     * @ORM\OneToMany(targetEntity="Membro", mappedBy="inscricao")
     */
    private $membros;

    /**
     * @ORM\Column(type="boolean")
     */
    private $depositoIdentificado = false;

    public function __construct() {
        $this->membros = new \Doctrine\Common\Collections\ArrayCollection();
        $this->id = strtoupper(substr(uniqid(), -6, 6));
    }

    function getId() {
        return $this->id;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getUf() {
        return $this->uf;
    }

    function getDiretor() {
        return $this->diretor;
    }

    function getEmail() {
        return $this->email;
    }

    function getCamisetasAvulsasInfantilP() {
        return $this->camisetasAvulsasInfantilP;
    }

    function getCamisetasAvulsasInfantilM() {
        return $this->camisetasAvulsasInfantilM;
    }

    function getCamisetasAvulsasInfantilG() {
        return $this->camisetasAvulsasInfantilG;
    }

    function getCamisetasAvulsasP() {
        return $this->camisetasAvulsasP;
    }

    function getCamisetasAvulsasM() {
        return $this->camisetasAvulsasM;
    }

    function getCamisetasAvulsasG() {
        return $this->camisetasAvulsasG;
    }

    function getCamisetasAvulsasGG() {
        return $this->camisetasAvulsasGG;
    }

    function getCamisetasAvulsasXG() {
        return $this->camisetasAvulsasXG;
    }

    function getCamisetasAvulsasXXG() {
        return $this->camisetasAvulsasXXG;
    }

    function getCamisetasAvulsasXXXG() {
        return $this->camisetasAvulsasXXXG;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
        return $this;
    }

    function setUf($uf) {
        $this->uf = $uf;
        return $this;
    }

    function setDiretor($diretor) {
        $this->diretor = $diretor;
        return $this;
    }

    function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    function setCamisetasAvulsasInfantilP($camisetasAvulsasInfantilP) {
        $this->camisetasAvulsasInfantilP = $camisetasAvulsasInfantilP;
        return $this;
    }

    function setCamisetasAvulsasInfantilM($camisetasAvulsasInfantilM) {
        $this->camisetasAvulsasInfantilM = $camisetasAvulsasInfantilM;
        return $this;
    }

    function setCamisetasAvulsasInfantilG($camisetasAvulsasInfantilG) {
        $this->camisetasAvulsasInfantilG = $camisetasAvulsasInfantilG;
        return $this;
    }

    function setCamisetasAvulsasP($camisetasAvulsasP) {
        $this->camisetasAvulsasP = $camisetasAvulsasP;
        return $this;
    }

    function setCamisetasAvulsasM($camisetasAvulsasM) {
        $this->camisetasAvulsasM = $camisetasAvulsasM;
        return $this;
    }

    function setCamisetasAvulsasG($camisetasAvulsasG) {
        $this->camisetasAvulsasG = $camisetasAvulsasG;
        return $this;
    }

    function setCamisetasAvulsasGG($camisetasAvulsasGG) {
        $this->camisetasAvulsasGG = $camisetasAvulsasGG;
        return $this;
    }

    function setCamisetasAvulsasXG($camisetasAvulsasXG) {
        $this->camisetasAvulsasXG = $camisetasAvulsasXG;
        return $this;
    }

    function setCamisetasAvulsasXXG($camisetasAvulsasXXG) {
        $this->camisetasAvulsasXXG = $camisetasAvulsasXXG;
        return $this;
    }

    function setCamisetasAvulsasXXXG($camisetasAvulsasXXXG) {
        $this->camisetasAvulsasXXXG = $camisetasAvulsasXXXG;
        return $this;
    }

    function getMembros() {
        return $this->membros;
    }

    function setMembros($membros) {
        $this->membros = $membros;
        return $this;
    }

    function getDepositoIdentificado() {
        return $this->depositoIdentificado;
    }

    function setDepositoIdentificado($depositoIdentificado) {
        $this->depositoIdentificado = $depositoIdentificado;
        return $this;
    }

}
