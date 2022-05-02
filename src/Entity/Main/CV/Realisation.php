<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\RealisationRepository;
use App\Twig\Cache\CacheableInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;


#[UniqueEntity(fields: "name", errorPath: "name")]
#[Entity(repositoryClass: RealisationRepository::class)]
#[Table(name: 'CV_Realisation')]
class Realisation implements CacheableInterface
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: 'integer')]
    protected int $id;
    #[Column(name: 'NomRealisation', type: 'string', length: 255, unique: true)]
    #[NotBlank]
    #[Length(max: 255)]
    protected string $name;
    #[Column(name: 'DescriptionRealisation', type: 'text')]
    #[NotBlank]
    protected string $description;
    #[Column(name: 'EntrepriseRealisation', type: 'string', length: 32, nullable: true)]
    #[Length(max: 32)]
    protected ?string $company;
    #[Column(name: 'DateMiseEnLigneRealisation', type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $dateRelease;
    #[Column(name: 'TempsRealisation', type: 'string', length: 32, nullable: true)]
    #[Length(max: 32)]
    protected ?string $timeToMake;
    #[Column(name: 'LienRealisation', type: 'string', length: 255, nullable: true)]
    #[Length(max: 255)]
    #[Url]
    protected ?string $link;
    #[OneToOne(mappedBy: 'realisation', targetEntity: 'App\Entity\Main\CV\RealisationImageMiniature', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[JoinColumn(nullable: true)]
    protected ?RealisationImageMiniature $mainImage;
    /**
     * @var Collection<RealisationImageGallerie>
     */
    #[OneToMany(mappedBy: 'realisations', targetEntity: 'App\Entity\Main\CV\RealisationImageGallerie', orphanRemoval: true)]
    protected Collection $gallery;
    #[Column(name: 'EnLigne', type: 'boolean', options: ['default' => false])]
    protected bool $public;
    #[Column(type: 'string', length: 255, unique: true, nullable: false)]
    protected string $slug;
    #[Column(name: 'Resume', type: 'string', nullable: true)]
    protected ?string $preface;
    /**
     *
     * @var Collection<Technologie>
     */
    #[ManyToMany(targetEntity: 'App\Entity\Main\CV\Technologie')]
    #[JoinTable]
    #[JoinColumn(referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(referencedColumnName: 'NumTechnologie')]
    protected Collection $technologies;
    #[Column(name: 'RealisationEdition', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $dateModified;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->mainImage = null;
        $this->timeToMake = null;
        $this->company = null;
        $this->dateRelease = null;
        $this->link = null;
        $this->public = false;
        $this->dateModified = new DateTime();
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDateRelease(): ?DateTimeInterface
    {
        return $this->dateRelease;
    }

    public function setDateRelease(?DateTimeInterface $dateRelease): self
    {
        $this->dateRelease = $dateRelease;

        return $this;
    }

    public function getTimeToMake(): ?string
    {
        return $this->timeToMake;
    }

    public function setTimeToMake(?string $timeToMake): self
    {
        $this->timeToMake = $timeToMake;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getMainImage(): ?RealisationImageMiniature
    {
        return $this->mainImage;
    }

    public function setMainImage(?RealisationImageMiniature $mainImage): self
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * @return Collection<RealisationImageGallerie>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(RealisationImageGallerie $gallery): self
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery[] = $gallery;
            $gallery->setRealisation($this);
        }

        return $this;
    }

    public function removeGallery(RealisationImageGallerie $gallery): self
    {
        if ($this->gallery->removeElement($gallery)) {
            // set the owning side to null (unless already changed)
            if ($gallery->getRealisation() === $this) {
                unset($gallery);
            }
        }

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function CompleteSlug(SluggerInterface $slugger): void
    {
        if (null === $this->getSlugOrNUll() || '-' == $this->getSlugOrNUll()) {
            $this->slug = (string)$slugger->slug($this->getId() . ' ' . $this->getName())->lower();
        }
    }

    public function getSlugOrNUll(): ?string
    {
        if (!isset($this->slug)) {
            return null;
        }

        return $this->slug;
    }

    public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPreface(): ?string
    {
        return $this->preface;
    }

    public function setPreface(?string $preface): self
    {
        $this->preface = $preface;

        return $this;
    }

    /**
     * @return Collection|Technologie[]
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    public function addTechnologies(Technologie $technologie): self
    {
        if (!$this->technologies->contains($technologie)) {
            $this->technologies[] = $technologie;
        }

        return $this;
    }

    public function removeTechnologies(Technologie $technologie): self
    {
        if ($this->technologies->contains($technologie)) {
            $this->technologies->removeElement($technologie);
        }

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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
