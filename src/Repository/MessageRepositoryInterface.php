<?php

declare(strict_types=1);

namespace App\Repository;

use App\Enum\MessageStatusEnum;

interface MessageRepositoryInterface
{
    /**
     * @return
     */
    public function getAllByStatus(MessageStatusEnum $messageStatusEnum): array;
}
