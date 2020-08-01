<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\CompetenceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompetenceRepository::class)
 * @ORM\Table(name="CV_Competence")
 */
class Competence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="CodeCompetence")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CompetenceCategorie::class, inversedBy="competences")
     * @ORM\JoinColumn(name="NumCompetenceCategorie", nullable=false, referencedColumnName="NumCompetenceCategorie")
     */
    private $Categorie;

    /**
     * @ORM\OneToOne(targetEntity=Technologie::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, name="NumTechnologie", referencedColumnName="NumTechnologie")
     */
    private $Technologie;

    /**
     * @ORM\Column(type="string", length=16, name="NiveauCompetence")
     */
    private $niveau;

    /**
     * @ORM\Column(type="boolean", name="EstScolaire")
     */
    private $scolaire;

    /**
     * @ORM\Column(type="boolean", name="EstAutoDitacte")
     */
    private $autoditacte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?CompetenceCategorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?CompetenceCategorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getTechnologie(): ?Technologie
    {
        return $this->Technologie;
    }

    public function setTechnologie(Technologie $Technologie): self
    {
        $this->Technologie = $Technologie;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getScolaire(): ?bool
    {
        return $this->scolaire;
    }

    public function setScolaire(bool $scolaire): self
    {
        $this->scolaire = $scolaire;

        return $this;
    }

    public function getAutoditacte(): ?bool
    {
        return $this->autoditacte;
    }

    public function setAutoditacte(bool $autoditacte): self
    {
        $this->autoditacte = $autoditacte;

        return $this;
    }
}
