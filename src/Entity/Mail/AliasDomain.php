<?php

namespace App\Entity\Mail;

use App\Repository\Mail\AliasDomainRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AliasDomainRepository::class)
 * @ORM\Table(name="postfix_alias_domain")
 */
class AliasDomain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class)
     * @ORM\JoinColumn(nullable=false, name="domain_origin_id")
     */
    private Domain $domainOrigin;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class)
     * @ORM\JoinColumn(nullable=false, name="domain_target_id")
     */
    private Domain $domainTarget;

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

    public function getDomainOrigin(): Domain
    {
        return $this->domainOrigin;
    }

    public function setDomainOrigin(Domain $domainOrigin): self
    {
        $this->domainOrigin = $domainOrigin;

        return $this;
    }

    public function getDomainTarget(): Domain
    {
        return $this->domainTarget;
    }

    public function setDomainTarget(Domain $domainTarget): self
    {
        $this->domainTarget = $domainTarget;

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
