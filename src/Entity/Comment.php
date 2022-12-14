<?php

namespace App\Entity;

use App\Enum\CommentStateType;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $body = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $state = 0; // CommentStateType::DRAFT

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getState(): ?CommentStateType
    {
/*
        // при ошибке будет исключение
        return match($this->state) {
            0 => CommentStateType::DRAFT,
            1 => CommentStateType::SUBMITTED,
            2 => CommentStateType::SPAM,
            3 => CommentStateType::HAM,
            4 => CommentStateType::REJECTED,
            5 => CommentStateType::PUBLISHED
        };
*/
        // вернёт null при ошибке
//        return CommentStateType::tryFrom($this->state);
        // при ошибке будет исключение
        return CommentStateType::from($this->state);
    }
/*
    public function setState(?CommentStateType $state): self
    {
        $this->state = $state->value;

        return $this;
    }
*/
    public function getPublishingPlace(): ?string
    {
        return match($this->state) {
            0 => 'draft',
            1 => 'submitted',
            2 => 'spam',
            3 => 'ham',
            4 => 'rejected',
            5 => 'published'
        };
    }

    public function setPublishingPlace(string $publishingPlace): self
    {
        $this->state =  match($publishingPlace) {
            'draft' => 0,
            'submitted' => 1,
            'spam' => 2,
            'ham' => 3,
            'rejected' => 4,
            'published' => 5
        };

        return $this;
    }
}
