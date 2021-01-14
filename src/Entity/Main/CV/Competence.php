<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\CompetenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ORM\Table(name="CV_Competence")
 * @UniqueEntity(
 *     fields={"technologie"},
 *     errorPath="technologie",
 * )
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="CodeCompetence")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompetenceCategorie::class, inversedBy="competences")
     * @ORM\JoinColumn(name="NumCompetenceCategorie", nullable=false, referencedColumnName="NumCompetenceCategorie")
     * @Assert\NotNull
     */
    private CompetenceCategorie $categorie;

    /**
     * @ORM\OneToOne(targetEntity=Technologie::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, name="NumTechnologie", referencedColumnName="NumTechnologie")
     * @Assert\NotNull
     */
    private Technologie $technologie;

    /**
     * @ORM\Column(type="boolean", name="EstScolaire")
     */
    private bool $scolaire;

    /**
     * @ORM\Column(type="boolean", name="EstAutoDitacte")
     */
    private bool $autoditacte;

    /**
     * @ORM\ManyToOne(targetEntity=CompetenceNiveau::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private CompetenceNiveau $niveau;

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

    public function setNiveau(CompetenceNiveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }
}
