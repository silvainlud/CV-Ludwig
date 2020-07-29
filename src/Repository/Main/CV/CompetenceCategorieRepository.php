<?php

namespace App\Repository\Main\CV;

use App\Entity\Main\CV\CompetenceCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetenceCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenceCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenceCategorie[]    findAll()
 * @method CompetenceCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenceCategorie::class);
    }

    // /**
    //  * @return CompetenceCategorie[] Returns an array of CompetenceCategorie objects
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
    public function findOneBySomeField($value): ?CompetenceCategorie
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
