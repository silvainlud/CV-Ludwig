<?php

namespace App\Entity\Mail;

use App\Repository\Mail\DomainRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DomainRepository::class)
 * @ORM\Table(name="postfix_domain")
 */
class Domain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $domain;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="integer")
     */
    private int $nb_aliases;

    /**
     * @ORM\Column(type="integer")
     */
    private int $nb_mailboxes;

    /**
     * @ORM\Column(type="bigint")
     */
    private int $maxquota;

    /**
     * @ORM\Column(type="bigint")
     */
    private int $quota;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $backupMX;

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

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbAliases(): int
    {
        return $this->nb_aliases;
    }

    public function setNbAliases(int $nb_aliases): self
    {
        $this->nb_aliases = $nb_aliases;

        return $this;
    }

    public function getNbMailboxes(): int
    {
        return $this->nb_mailboxes;
    }

    public function setNbMailboxes(int $nb_mailboxes): self
    {
        $this->nb_mailboxes = $nb_mailboxes;

        return $this;
    }

    public function getMaxquota(): ?int
    {
        return $this->maxquota;
    }

    public function setMaxquota(int $maxquota): self
    {
        $this->maxquota = $maxquota;

        return $this;
    }

    public function getQuota(): ?int
    {
        return $this->quota;
    }

    public function setQuota(int $quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    public function getBackupMX(): ?bool
    {
        return $this->backupMX;
    }

    public function setBackupMX(bool $backupMX): self
    {
        $this->backupMX = $backupMX;

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
}
