<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImageTag
 *
 * @ORM\Table(name="image_tag")
 * @ORM\Entity
 */
class ImageTag {
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_image", type="integer", nullable=true)
     */
    private $idImage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_tag", type="integer", nullable=true)
     */
    private $idTag;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdImage(): ?int
    {
        return $this->idImage;
    }

    public function setIdImage(?int $idImage): self
    {
        $this->idImage = $idImage;

        return $this;
    }

    public function getIdTag(): ?int
    {
        return $this->idTag;
    }

    public function setIdTag(?int $idTag): self
    {
        $this->idTag = $idTag;

        return $this;
    }


}
