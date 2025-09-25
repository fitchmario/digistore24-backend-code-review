<?php

declare(strict_types=1);

namespace App\Tests\Message\Command;


use App\Entity\Message;
use App\Enum\MessageStatusEnum;
use App\Message\Command\SendMessage;
use App\Message\Command\SendMessageHandler;
use App\Repository\MessageRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class SendMessageHandlerTest extends TestCase
{
    public function test_it_creates_and_saves_a_message(): void
    {
        $text = 'Hello World';

        $command = new SendMessage($text);
        $uuid = $command->getUuid();

        $repository = $this->createMock(MessageRepositoryInterface::class);

        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Message $message) use ($uuid, $text) {
                return $message->getUuid() === $uuid
                    && $message->getText() === $text
                    && $message->getStatus() === MessageStatusEnum::SENT->value;
            }));

        $handler = new SendMessageHandler($repository);
        $handler($command);
    }
}
