<?php

declare(strict_types=1);

namespace App\Presentation\Model;

class MessageModel
{
    public function __construct(
        private string $uuid,
        private string $text,
        private string $status
    ){}

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getStatus(): string
    {
        return $this->status;
    }


}
