<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @var int|null
     *
     * @Serializer\Accessor(getter="getCount",setter="setCount")
     * @ORM\Column(name="image_count", type="integer", nullable=false)
     */
    private ?int $count = NULL;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @var User
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private User $createdBy;

    /**
     * @var DateTime|null
     * @Serializer\Type(name="DateTime")
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private ?DateTime $createdOn;

    /**
     * @var TypeTag
     *
     * @ORM\ManyToOne(targetEntity="TypeTag", inversedBy="tags", cascade={"persist"})
     * @ORM\JoinColumn(name="id_type", referencedColumnName="id")
     */
    private TypeTag $type;


    /**
     * @var ?Collection
     *
     * @ORM\ManyToMany(targetEntity="Image", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinTable(name="image_tag",
     *     joinColumns={@ORM\JoinColumn(name="id_tag", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_image", referencedColumnName="id", unique=true)}
     *     )
     */
    private ?Collection $images;


    public function __construct() {
        $this->createdOn = new DateTime();
        $this->images = new ArrayCollection();
        $this->imageCount = count($this->images);
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function getType(): ?TypeTag {
        return $this->type;
    }

    public function setType(?TypeTag $type): self {
        $this->type = $type;
        return $this;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getCount(): int {
        return count($this->images);
    }

    public function setCount(?int $count): self {
        $this->count = $count;
        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getCreatedBy(): ?User {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): self {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface {
        return $this->createdOn;
    }

    public function setCreatedOn(?\DateTimeInterface $createdOn): self {
        $this->createdOn = $createdOn;
        return $this;
    }

    public function getImages(): Collection{
        return $this->images;
    }


}
