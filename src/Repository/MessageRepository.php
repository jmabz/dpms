<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Retrieve messages that involve a user with the specified ID
     *
     * @param integer $userId
     * @return void
     */
    public function findMessagesWithUser(int $userId)
    {
        $querybuilder = $this->createQueryBuilder('m')
            ->andwhere('(s.id = :userId and m.isArchivedBySender = false)
                        or (r.id = :userId and m.isArchivedByRecepient = false)')
            ->andWhere('(s.id = :userId and m.isSenderCopyDeleted = false)
                        or (r.id = :userId and m.isRecepientCopyDeleted = false)')
        ;

        return $querybuilder
            ->orderBy('m.dateSent', 'DESC')
            ->join('m.sender', 's')
            ->join('m.recepient', 'r')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retrieve archived messages that involve a user with the specified ID
     *
     * @param integer $userId
     * @return void
     */
    public function findArchivedMessagesWithUser(int $userId)
    {
        $querybuilder = $this->createQueryBuilder('m')
            ->andwhere('(s.id = :userId and m.isArchivedBySender = true)
                        or (r.id = :userId and m.isArchivedByRecepient = true)')
            ->andWhere('(s.id = :userId and m.isSenderCopyDeleted = false)
                        or (r.id = :userId and m.isRecepientCopyDeleted = false)')
        ;

        return $querybuilder
            ->orderBy('m.dateSent', 'DESC')
            ->join('m.sender', 's')
            ->join('m.recepient', 'r')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult()
        ;
    }
}
