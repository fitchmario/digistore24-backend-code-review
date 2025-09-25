<?php

namespace App\Repository;

use App\Entity\Message;
use App\Enum\MessageStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository implements MessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @inheritdoc
     */
    public function getAllByStatus(?MessageStatusEnum $messageStatusEnum): array
    {
        $qb = $this->createQueryBuilder('m')
        ->orderBy('m.createdAt', 'DESC');

        if ($messageStatusEnum !== null) {
            $qb->andWhere('m.status LIKE :status')
                ->setParameter('status', $messageStatusEnum->value);
        }

        /** @var Message[] $messages */
        $messages = $qb->getQuery()->getResult();

        return $messages;
    }

    /**
     * @inheritdoc
     */
    public function save(Message $message): void
    {
        $this->getEntityManager()->persist($message);
        $this->getEntityManager()->flush();
    }
}
