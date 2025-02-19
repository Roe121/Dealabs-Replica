<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\DealStatusEnum;
use App\Repository\DealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DealRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Deal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'deals')]
    #[ORM\JoinColumn]
    private Category $category;

    #[ORM\ManyToOne(inversedBy: 'deals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'deal', orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'deals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Merchant $merchant = null;

    #[ORM\Column]
    private ?int $originalPrice = null;

    #[ORM\Column(length: 2083)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: "deal_images", fileNameProperty: "image")]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiredAt = null;

    #[ORM\Column(enumType: DealStatusEnum::class)]
    private ?DealStatusEnum $status = null;

    #[ORM\Column]
    private ?int $hotScore = null;

    #[ORM\Column(nullable: true)]
    private ?int $deliveryPrice = null;

    #[ORM\OneToMany(targetEntity: Vote::class, mappedBy: 'deal', orphanRemoval: true)]
    private Collection $votes;

    public function __construct()
    {
        $this->status = DealStatusEnum::PENDING;
        $this->hotScore = 0;
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setDeal($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getDeal() === $this) {
                $comment->setDeal(null);
            }
        }

        return $this;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): static
    {
        $this->merchant = $merchant;

        return $this;
    }

    public function getOriginalPrice(): ?int
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(int $originalPrice): static
    {
        $this->originalPrice = $originalPrice;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeImmutable
    {
        return $this->expiredAt;
    }

    public function setExpiredAt(?\DateTimeImmutable $expiredAt): static
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    public function getStatus(): ?DealStatusEnum
    {
        return $this->status;
    }

    public function setStatus(DealStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getHotScore(): ?int
    {
        return $this->hotScore;
    }

    public function setHotScore(int $hotScore): static
    {
        $this->hotScore = $hotScore;

        return $this;
    }

    public function getDeliveryPrice(): ?int
    {
        return $this->deliveryPrice;
    }

    public function setDeliveryPrice(int $deliveryPrice): static
    {
        $this->deliveryPrice = $deliveryPrice;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if ($imageFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }
}
