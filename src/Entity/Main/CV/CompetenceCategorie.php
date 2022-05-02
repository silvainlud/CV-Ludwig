<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotNull;
use DateTimeInterface;
use Doctrine\ORM\Mapping\OneToMany;
use DateTime;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use App\Repository\Main\CV\CompetenceCategorieRepository;
use App\Twig\Cache\CacheableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: CompetenceCategorieRepository::class)]
#[Table(name: 'CV_CompetenceCategorie')]
#[HasLifecycleCallbacks]
class CompetenceCategorie implements CacheableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'NumCompetenceCategorie', type: 'integer')]
    private int $id;
    #[Column(name: 'NomCompetenceCategorie', type: 'string', length: 64, unique: true)]
    #[Length(max: 64)]
    #[NotBlank]
    private string $name;
    #[Column(name: 'OrdreCompetenceCategorie', type: 'integer', nullable: true)]
    #[GreaterThanOrEqual(value: 0)]
    #[NotNull]
    private ?int $ordre;
    #[Column(name: 'CompetenceCategorieEdition', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $dateModified;
    /**
     * @var Collection<Competence>
     */
    #[OneToMany(mappedBy: 'categorie', targetEntity: Competence::class)]
    private Collection $competences;
    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->dateModified = new DateTime();
    }
    public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }

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
        }

        return $this;
    }
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->dateModified;
    }
    #[PrePersist]
    public function prePersist() : void
    {
        $this->preUpdate();
    }
    #[PreUpdate]
    public function preUpdate() : void
    {
        $this->dateModified = new DateTime();
    }
}
