<?php

namespace App\Repository;

use App\Entity\Message;
use App\Enum\MessageStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
        $qb = $this->createQueryBuilder('m');

        if ($messageStatusEnum !== null) {
            $qb->andWhere('m.status = :status')
                ->setParameter('status', $messageStatusEnum->value);
        }

        return $qb->getQuery()->getResult();
    }
}
