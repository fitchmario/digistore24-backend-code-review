<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use App\Enum\MessageStatusEnum;

interface MessageRepositoryInterface
{
    /**
     * @return Message[]
     */
    public function getAllByStatus(MessageStatusEnum $messageStatusEnum): array;

    public function save(Message $message): void;
}
