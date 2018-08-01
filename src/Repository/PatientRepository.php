<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PatientRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Patient::class);
    }

   public function findAllPatientsPaged($curPage = 1)
   {
        $query = $this->createQueryBuilder('p')
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