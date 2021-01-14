<?php

namespace App\Entity\Mail;

use App\Repository\Mail\AliasRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AliasRepository::class)
 * @ORM\Table(name="postfix_alias")
 */
class Alias
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
     * @ORM\Column(type="string", length=64)
     */
    private string $adress;

    /**
     * @ORM\Column(type="text")
     */
    private string $goto;

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

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getAdress(): string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getGoto(): string
    {
        return $this->goto;
    }

    public function setGoto(string $goto): self
    {
        $this->goto = $goto;

        return $this;
    }

    public function getDateCreated(): DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateModified(): DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(DateTimeInterface $date_modified): self
    {
        $this->date_modified = $date_modified;

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
