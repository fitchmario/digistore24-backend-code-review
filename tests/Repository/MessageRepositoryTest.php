<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Message;
use App\Enum\MessageStatusEnum;
use App\Repository\MessageRepository;
use App\Repository\MessageRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class MessageRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private MessageRepositoryInterface $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        $this->em = $em;

        /** @var MessageRepositoryInterface $repo */
        $repo = $container->get(MessageRepositoryInterface::class);
        $this->repository = $repo;

        $connection = $this->em->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeStatement(
            $platform->getTruncateTableSQL('message', true)
        );
    }

    public function test_it_returns_all_messages_when_status_is_null(): void
    {
        $m1 = Message::make(
            Uuid::v6()->toRfc4122(),
            "Hello",
            MessageStatusEnum::SENT
        );
        $m2 = Message::make(
            Uuid::v6()->toRfc4122(),
            "World",
            MessageStatusEnum::READ)
        ;

        $this->repository->save($m1);
        $this->repository->save($m2);

        $results = $this->repository->getAllByStatus(null);

        $this->assertCount(2, $results);
        $this->assertContainsOnlyInstancesOf(Message::class, $results);
    }

    public function test_it_filters_by_status_sent(): void
    {
        $sent = Message::make(
            Uuid::v6()->toRfc4122(),
            "Sent",
            MessageStatusEnum::SENT
        );
        $read = Message::make(
            Uuid::v6()->toRfc4122(),
            "Read",
            MessageStatusEnum::READ
        );

        $this->repository->save($sent);
        $this->repository->save($read);

        $results = $this->repository->getAllByStatus(MessageStatusEnum::SENT);

        $this->assertCount(1, $results);
        $this->assertSame('Sent', $results[0]->getText());
        $this->assertSame(MessageStatusEnum::SENT->value, $results[0]->getStatus());
    }

    public function test_it_filters_by_status_read(): void
    {
        $sent = Message::make(
            Uuid::v6()->toRfc4122(),
            "Sent",
            MessageStatusEnum::SENT
        );
        $read = Message::make(
            Uuid::v6()->toRfc4122(),
            "Read",
            MessageStatusEnum::READ
        );

        $this->repository->save($sent);
        $this->repository->save($read);

        $results = $this->repository->getAllByStatus(MessageStatusEnum::READ);

        $this->assertCount(1, $results);
        $this->assertSame('Read', $results[0]->getText());
        $this->assertSame(MessageStatusEnum::READ->value, $results[0]->getStatus());
    }
}
