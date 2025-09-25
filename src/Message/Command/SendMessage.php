<?php
declare(strict_types=1);

namespace App\Message\Command;

use Symfony\Component\Uid\Uuid;

class SendMessage
{
    private string $uuid;

    public function __construct(
        private string $text,
    )
    {
        $this->uuid = Uuid::v6()->toRfc4122();
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getText(): string
    {
        return $this->text;
    }

}
