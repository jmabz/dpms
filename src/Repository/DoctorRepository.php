<?php

namespace App\Repository;

use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctorRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

   public function findDoctorsInClinic($clinicId)
   {
        $qb = $this->createQueryBuilder('d');

        $nots = $this->createQueryBuilder('d')
            ->select('d.id')
            ->andWhere('c.id = :id')
            ->join('d.clinics', 'c')

            ;

        return $this->createQueryBuilder('x')
            ->where($qb->expr()->in('x.id', $nots->getDQL()))
            ->setParameter('id', $clinicId)
            ;
   }

   public function findDoctorsNotInClinic($clinicId)
   {
        $qb = $this->createQueryBuilder('d');

        $nots = $this->createQueryBuilder('d')
            ->select('d.id')
            ->andWhere('c.id = :id')
            ->join('d.clinics', 'c')

            ;

        return $this->createQueryBuilder('x')
            ->where($qb->expr()->notIn('x.id', $nots->getDQL()))
            ->setParameter('id', $clinicId)
            ;
   }

   public function findAllDoctorsPaged($curPage = 1)
    {
        $query = $this->createQueryBuilder('d')
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