<?php

namespace App\Repository\Mail;

use App\Entity\Mail\PostfixUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostfixUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostfixUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostfixUser[]    findAll()
 * @method PostfixUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostfixUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostfixUser::class);
    }

    // /**
    //  * @return PostfixUser[] Returns an array of PostfixUser objects
    //  */
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
    public function findOneBySomeField($value): ?PostfixUser
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
