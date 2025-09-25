<?php

declare(strict_types=1);

namespace App\Message\Query;

use App\Presentation\Model\MessageCollection;
use App\Presentation\Service\MessageBuilder;
use App\Repository\MessageRepositoryInterface;

class GetAllMessagesQueryHandler
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository,
        private MessageBuilder $messageBuilder,
    )
    {
    }

    public function handle(GetAllMessagesQuery $query): MessageCollection
    {
        $messages = $this->messageRepository->getAllByStatus($query->getStatus());

        return $this->messageBuilder->buildCollection($messages);
    }
}
