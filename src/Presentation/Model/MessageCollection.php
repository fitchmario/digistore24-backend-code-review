<?php

declare(strict_types=1);

namespace App\Presentation\Model;

class MessageCollection
{

    /**
     * @param MessageModel[] $messages
     */
    public function __construct(private array $messages = [])
    {

    }

    public function addMessage(MessageModel $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
