<?php

namespace App\Entity;

use App\Enum\MessageStatusEnum;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @phpstan-ignore-next-line */
    private int $id;

    #[ORM\Column(type: Types::GUID)]
    private string $uuid;

    #[ORM\Column(length: 255)]
    private string $text;

    #[ORM\Column(length: 255, nullable: true)]
    private string $status;
    
    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    public static function make(
        string $uuid,
        string $text,
        MessageStatusEnum $status
    ): self
    {
        $self = new self();
        $self->setUuid($uuid);
        $self->setText($text);
        $self->setStatus($status);

        return $self;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(MessageStatusEnum $status): static
    {
        $this->status = $status->value;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new DateTime();
    }
}
