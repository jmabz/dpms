<?php

namespace App\Repository;

use App\Entity\AccreditationInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccreditationInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccreditationInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccreditationInfo[]    findAll()
 * @method AccreditationInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccreditationInfoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccreditationInfo::class);
    }

//    /**
//     * @return AccreditationInfo[] Returns an array of AccreditationInfo objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccreditationInfo
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
