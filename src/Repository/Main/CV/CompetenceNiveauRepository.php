<?php

namespace App\Repository\Main\CV;

use App\Entity\Main\CV\CompetenceNiveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetenceNiveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenceNiveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenceNiveau[]    findAll()
 * @method CompetenceNiveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceNiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenceNiveau::class);
    }

    // /**
    //  * @return CompetenceNiveau[] Returns an array of CompetenceNiveau objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompetenceNiveau
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
