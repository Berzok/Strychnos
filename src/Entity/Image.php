<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Image
 *
 * @ORM\Table(name="image", uniqueConstraints={@ORM\UniqueConstraint(name="filename", columns={"filename"}), @ORM\UniqueConstraint(name="source", columns={"source"})})
 * @ORM\Entity(repositoryClass=App\Repository\ImageRepository::class)
 */
class Image {

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
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaires", type="string", length=255, nullable=true)
     */
    private $commentaires;

    /**
     * @var string|null
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @var int|null
     *
     * @ORM\Column(name="uploaded_by", type="integer", nullable=true)
     */
    private $uploadedBy;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="uploaded_on", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private ?DateTime $uploadedOn;

    /**
     * @var ?Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="image_tag",
     *     joinColumns={@ORM\JoinColumn(name="id_image", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_tag", referencedColumnName="id", unique=true)}
     *     )
     */
    private ?Collection $tags;

    /**
     * @var ?Artist
     * @Serializer\MaxDepth(1)
     *
     * @ORM\ManyToOne(targetEntity="Artist", inversedBy="images", cascade={"persist"})
     * @ORM\JoinColumn(name="id_artist", referencedColumnName="id")
     */
    private $artist;


    public function getId(): ?int {
        return $this->id;
    }

    public function getArtist(): ?Artist {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self {
        $this->artist = $artist;
        return $this;
    }

    public function getFilename(): ?string {
        return $this->filename;
    }

    public function setFilename(?string $filename): self {
        $this->filename = $filename;
        return $this;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function setUrl(?string $url): self {
        $this->url = $url;
        return $this;
    }

    public function getCommentaires(): ?string {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): self {
        $this->commentaires = $commentaires;
        return $this;
    }

    public function getSource(): ?string {
        return $this->source;
    }

    public function setSource(?string $source): self {
        $this->source = $source;

        return $this;
    }

    public function getUploadedBy(): ?int {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?int $uploadedBy): self {
        $this->uploadedBy = $uploadedBy;
        return $this;
    }

    public function getUploadedOn(): ?\DateTimeInterface {
        return $this->uploadedOn;
    }

    public function setUploadedOn(?\DateTimeInterface $uploadedOn): self {
        $this->uploadedOn = $uploadedOn;
        return $this;
    }


    /**
     * @return Collection
     */
    public function getTags(): Collection {
        return $this->tags;
    }

    /**
     * @param Collection $tags
     * @return Personnage
     */
    public function setTags(Collection $tags): Image {
        $this->tags = $tags;
        return $this;
    }

    public function addTag(Tag $tag): self {
        if ($this->tags === null) {
            $this->tags = new ArrayCollection();
        }
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            //$tag->setImage($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            /*
            if ($tag->getPersonnage() === $this) {
                $tag->setPersonnage(null);
            }
            */
        }

        return $this;
    }

}
