<?php

namespace App\Repository\Main\CV;

use App\Entity\Main\CV\Realisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Realisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Realisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Realisation[]    findAll()
 * @method Realisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Realisation::class);
    }

    public function findBySlug(string $value): ?Realisation
    {
        try {
            return $this->createQueryBuilder('t')
                ->andWhere('t.slug = :slug')
                ->setParameter('slug', $value)
                ->andWhere('t.public = :true')
                ->setParameter('true', true)
                ->orderBy('t.name', 'ASC')
                ->join('t.mainImage', 'mainImage')->addSelect('mainImage')
                ->leftJoin('t.technologies', 'technologies')->addSelect('technologies')
                ->leftJoin('technologies.linkedTechonologies', 'linkedTechonologies')->addSelect('linkedTechonologies')
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * @return array<Realisation>
     */
    public function findAllWithImage(): ?array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.public = :true')
            ->setParameter('true', true)
            ->orderBy('t.dateRelease', 'DESC')
            ->orderBy('t.name', 'ASC')
            ->join('t.mainImage', 'mainImage')->addSelect('mainImage')
            ->getQuery()
            ->getResult();
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
