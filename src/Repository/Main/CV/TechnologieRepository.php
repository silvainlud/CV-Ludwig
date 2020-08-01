<?php

namespace App\Repository\Main\CV;

use App\Entity\Main\CV\Technologie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Technologie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Technologie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Technologie[]    findAll()
 * @method Technologie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnologieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Technologie::class);
    }

    // /**
    //  * @return Technologie[] Returns an array of Technologie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Technologie
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
