<?php

namespace App\Entity\Mail;

use App\Repository\Mail\AliasDomainRepository;
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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class)
     * @ORM\JoinColumn(nullable=false, name="domain_origin_id")
     */
    private $domainOrigin;

    /**
     * @ORM\ManyToOne(targetEntity=Domain::class)
     * @ORM\JoinColumn(nullable=false, name="domain_target_id")
     */
    private $DomainTarget;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateÃ_modified;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomainOrigin(): ?Domain
    {
        return $this->domainOrigin;
    }

    public function setDomainOrigin(?Domain $domainOrigin): self
    {
        $this->domainOrigin = $domainOrigin;

        return $this;
    }

    public function getDomainTarget(): ?Domain
    {
        return $this->DomainTarget;
    }

    public function setDomainTarget(?Domain $DomainTarget): self
    {
        $this->DomainTarget = $DomainTarget;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateÃModified(): ?\DateTimeInterface
    {
        return $this->dateÃ_modified;
    }

    public function setDateÃModified(\DateTimeInterface $dateÃ_modified): self
    {
        $this->dateÃ_modified = $dateÃ_modified;

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
