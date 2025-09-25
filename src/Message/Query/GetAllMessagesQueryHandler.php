<?php

declare(strict_types=1);

namespace App\Message\Query;

use App\Presentation\Model\MessageCollection;
use App\Presentation\Service\MessageBuilder;
use App\Repository\MessageRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetAllMessagesQueryHandler
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository,
        private MessageBuilder $messageBuilder,
    )
    {
    }

    public function __invoke(GetAllMessagesQuery $query): MessageCollection
    {
        $messages = $this->messageRepository->getAllByStatus($query->getStatus());

        return $this->messageBuilder->buildCollection($messages);
    }
}
