<?php

declare(strict_types=1);

namespace App\Message\Query;

use App\Enum\MessageStatusEnum;

class GetAllMessagesQuery
{
    public function __construct(
        private ?MessageStatusEnum $status = null
    ) {}

    public function getStatus(): ?MessageStatusEnum
    {
        return $this->status;
    }
}
