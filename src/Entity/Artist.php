<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Artist
 *
 * @ORM\Table(name="artist", uniqueConstraints={@ORM\UniqueConstraint(name="id_account", columns={"id_account"})})
 * @ORM\Entity
 */
class Artist {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=55, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=111, nullable=true)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="id_account", type="integer", nullable=false)
     */
    private $idAccount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="account", type="string", length=255, nullable=true)
     */
    private $account;


    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Image", mappedBy="artist")
     */
    private $images;


    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(?string $url): self {
        $this->url = $url;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getIdAccount(): ?int {
        return $this->idAccount;
    }

    public function setIdAccount(int $idAccount): self {
        $this->idAccount = $idAccount;

        return $this;
    }

    public function getAccount(): ?string {
        return $this->account;
    }

    public function setAccount(?string $account): self {
        $this->account = $account;

        return $this;
    }


}
