<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\CompetenceRepository;
use App\Twig\Cache\CacheableInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\NotNull;

#[UniqueEntity(fields: "technologie", errorPath: "technologie")]
#[Entity(repositoryClass: CompetenceRepository::class)]
#[Table(name: 'CV_Competence')]
#[HasLifecycleCallbacks]
class Competence implements CacheableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'CodeCompetence', type: 'integer')]
    private int $id;
    #[ManyToOne(targetEntity: CompetenceCategorie::class, inversedBy: 'competences')]
    #[JoinColumn(name: 'NumCompetenceCategorie', referencedColumnName: 'NumCompetenceCategorie', nullable: false)]
    #[NotNull]
    private CompetenceCategorie $categorie;
    #[OneToOne(targetEntity: Technologie::class, cascade: ['persist', 'remove'])]
    #[JoinColumn(name: 'NumTechnologie', referencedColumnName: 'NumTechnologie', nullable: false)]
    #[NotNull]
    private Technologie $technologie;
    #[Column(name: 'EstScolaire', type: 'boolean')]
    private bool $scolaire;
    #[Column(name: 'EstAutoDitacte', type: 'boolean')]
    private bool $autoditacte;
    #[Column(name: 'CompetenceEdition', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $dateModified;
    #[ManyToOne(targetEntity: CompetenceNiveau::class)]
    #[JoinColumn(nullable: true)]
    private ?CompetenceNiveau $niveau;

    public function __construct()
    {
        $this->niveau = null;
        $this->dateModified = new DateTime();
    }

    public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
    }

    public function getCategorie(): CompetenceCategorie
    {
        return $this->categorie;
    }

    public function setCategorie(CompetenceCategorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getTechnologie(): Technologie
    {
        return $this->technologie;
    }

    public function setTechnologie(Technologie $technologie): self
    {
        $this->technologie = $technologie;

        return $this;
    }

    public function getScolaire(): bool
    {
        return $this->scolaire;
    }

    public function setScolaire(bool $scolaire): self
    {
        $this->scolaire = $scolaire;

        return $this;
    }

    public function getAutoditacte(): bool
    {
        return $this->autoditacte;
    }

    public function setAutoditacte(bool $autoditacte): self
    {
        $this->autoditacte = $autoditacte;

        return $this;
    }

    public function getNiveau(): ?CompetenceNiveau
    {
        return $this->niveau;
    }

    public function setNiveau(?CompetenceNiveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->dateModified;
    }

    #[PrePersist]
    public function prePersist(): void
    {
        $this->preUpdate();
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->dateModified = new DateTime();
    }

    public function postUpdate(): void
    {
        if (isset($this->categorie) && null != $this->categorie) {
            $this->categorie->preUpdate();
        }
    }
}
