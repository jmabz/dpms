<?php

namespace App\Repository;

use App\Entity\DiagnosisCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiagnosisCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiagnosisCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiagnosisCategory[]    findAll()
 * @method DiagnosisCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiagnosisCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiagnosisCategory::class);
    }

//    /**
//     * @return DiagnosisCategory[] Returns an array of DiagnosisCategory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DiagnosisCategory
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
   
    public function findAllDiagCategoriesPaged($curPage = 1)
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
