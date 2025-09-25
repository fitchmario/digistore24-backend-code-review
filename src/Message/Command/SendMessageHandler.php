<?php
declare(strict_types=1);

namespace App\Message\Command;

use App\Entity\Message;
use App\Enum\MessageStatusEnum;
use App\Repository\MessageRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendMessageHandler
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository
    )
    {
    }
    
    public function __invoke(SendMessage $sendMessage): void
    {
        $message = Message::make(
            $sendMessage->getUuid(),
            $sendMessage->getText(),
            MessageStatusEnum::SENT
        );

        $this->messageRepository->save($message);
    }
}
