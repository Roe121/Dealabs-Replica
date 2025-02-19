<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Comment;

#[ORM\Entity]
class CommentVote
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Comment::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'integer')]
    private int $type; // 1 = upvote, -1 = downvote

    public function getId(): ?int { return $this->id; }
    public function getComment(): ?Comment { return $this->comment; }
    public function setComment(?Comment $comment): self { $this->comment = $comment; return $this; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }
    public function getType(): int { return $this->type; }
    public function setType(int $type): self { $this->type = $type; return $this; }
}
