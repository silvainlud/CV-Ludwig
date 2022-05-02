<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use geertw\IpAnonymizer\IpAnonymizer;

/**
 * Class ContactLog.
 */
#[Entity(repositoryClass: 'App\Repository\Main\ContactLogRepository')]
class ContactLog
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: 'integer')]
    protected int $id;
    #[Column(type: 'string', length: 32)]
    protected string $ipAddress;
    #[Column(type: 'datetime')]
    protected DateTimeInterface $dateCreated;
    public function __construct(string $ipAddress)
    {
        $this->dateCreated = new DateTime();
        $this->ipAddress = (new IpAnonymizer())->anonymize($ipAddress);
    }
    public function getDateCreated(): DateTimeInterface
    {
        return $this->dateCreated;
    }
}
