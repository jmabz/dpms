<?php

namespace App\Repository;

use App\Entity\PatientRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PatientRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientRecord[]    findAll()
 * @method PatientRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRecordRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PatientRecord::class);
    }

//    /**
//     * @return PatientRecord[] Returns an array of PatientRecord objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PatientRecord
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
   
    public function findAllPatientRecordsPaged($patientId, $curPage = 1)
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('pt.id = :id')
            ->join('p.patient', 'pt')
            ->setParameter('id', $patientId)
            ->getQuery();

        // No need to manually get get the result ($query->getResult())

        $paginator = $this->paginate($query, $curPage);

        return $paginator;
    }

    public function paginate($dql, $page = 1, $limit = 10)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
        ->setFirstResult($limit * ($page - 1)) // Offset
        ->setMaxResults($limit); // Limit

    return $paginator;
    }
}
