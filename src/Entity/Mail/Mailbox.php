<?php

namespace App\Entity\Mail;

use App\Repository\Mail\MailboxRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailboxRepository::class)
 * @ORM\Table(name="postfix_mailbox")
 */
class Mailbox
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class)
     * @ORM\JoinColumn(nullable=false, name="domain_id")
     */
    private Domain $domain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private ?string $maildir;

    /**
     * @ORM\Column(type="bigint")
     */
    private ?int $quota;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $date_modified;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $active;

    public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getMaildir(): ?string
    {
        return $this->maildir;
    }

    public function setMaildir(?string $maildir): self
    {
        $this->maildir = $maildir;

        return $this;
    }

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(?int $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    public function getDateCreated(): ?DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateModified(): ?DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(DateTimeInterface $date_modified): self
    {
        $this->date_modified = $date_modified;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->username . '@' . $this->getDomain()->getDomain();
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }
}
