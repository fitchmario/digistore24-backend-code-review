<?php

declare(strict_types=1);

namespace App\Presentation\Model;

class UuidModel
{
    public function __construct(
        private string $uuid,
    ){}

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
