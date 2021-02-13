<?php

namespace App\Entity\Main;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use geertw\IpAnonymizer\IpAnonymizer;

/**
 * Class ContactLog.
 *
 * @ORM\Entity(repositoryClass="App\Repository\Main\ContactLogRepository")
 */
class ContactLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected int $id;
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected string $ipAddress;
    /**
     * @ORM\Column(type="datetime")
     */
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
