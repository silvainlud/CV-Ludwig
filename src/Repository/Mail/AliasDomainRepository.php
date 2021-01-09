<?php

namespace App\Repository\Mail;

use App\Entity\Mail\AliasDomain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AliasDomain|null find($id, $lockMode = null, $lockVersion = null)
 * @method AliasDomain|null findOneBy(array $criteria, array $orderBy = null)
 * @method AliasDomain[]    findAll()
 * @method AliasDomain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AliasDomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AliasDomain::class);
    }

    // /**
    //  * @return AliasDomain[] Returns an array of AliasDomain objects
    //  */
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
    public function findOneBySomeField($value): ?AliasDomain
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
