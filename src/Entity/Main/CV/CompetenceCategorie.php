<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\CompetenceCategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompetenceCategorieRepository::class)
 * @ORM\Table(name="CV_CompetenceCategorie")
 */
class CompetenceCategorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="guid", name="NumCompetenceCategorie")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, name="NomCompetenceCategorie", unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true, name="OrdreCompetenceCategorie")
     */
    private $ordre;

    /**
     * @ORM\OneToMany(targetEntity=Competence::class, mappedBy="Categorie")
     */
    private $competences;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(?int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->setCategorie($this);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
            // set the owning side to null (unless already changed)
            if ($competence->getCategorie() === $this) {
                $competence->setCategorie(null);
            }
        }

        return $this;
    }
}
