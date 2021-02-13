<?php

namespace App\Repository\Main;

use App\Entity\Main\ContactLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use geertw\IpAnonymizer\IpAnonymizer;

class ContactLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactLog::class);
    }

    public function findLastByIp(string $ipAddress): ?ContactLog
    {
        return $this->createQueryBuilder('contact')
            ->where('contact.ipAddress = :ip')->setParameter('ip', (new IpAnonymizer())->anonymize($ipAddress))
            ->orderBy('contact.dateCreated', 'DESC')
            ->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }
}
