<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 */
class Comprovante
{
    /** @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue(strategy="AUTO") */
    private $id;

    /** @ORM\Column(type="datetime") */
    private $data;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(
     *      maxSize = "1024k",
     *      maxSizeMessage = "O arquivo é muito grande ({{ size }} {{ suffix }}). Tamanho máximo permitido é {{ limit }} {{ suffix }}.",
     *      mimeTypes={ "application/pdf", "image/*"  },
     *      mimeTypesMessage = "Envie apenas PDF ou imagem."
     * )
     */
    private $arquivo;

    /**
     * @ORM\ManyToOne(targetEntity="Inscricao", inversedBy="comprovantes")
     * @ORM\JoinColumn(name="inscricao_id", referencedColumnName="id")
     */
    private $inscricao;

    public function __construct($inscricao)
    {
        $this->inscricao = $inscricao;
        $this->data = new \DateTime();
    }

    /**
     * Get the value of id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id.
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of data.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the value of inscricao.
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * Get the value of arquivo.
     */
    public function getArquivo()
    {
        return $this->arquivo;
    }

    /**
     * Set the value of arquivo.
     *
     * @return self
     */
    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;

        return $this;
    }
}
