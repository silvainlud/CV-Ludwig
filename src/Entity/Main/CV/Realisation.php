<?php

namespace App\Entity\Main\CV;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="CV_Realisation")
 */
class Realisation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true, name="NomRealisation")
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", name="DescriptionRealisation")
     * @Assert\NotBlank()
     */
    protected string $description;

    /**
     * @ORM\Column(type="string", nullable=true, name="EntrepriseRealisation", length=32)
     * @Assert\Length(max=32)
     */
    protected ?string $company;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="DateMiseEnLigneRealisation")
     */
    protected ?DateTimeInterface $dateRelease;

    /**
     * @ORM\Column(type="string", nullable=true, name="TempsRealisation", length=32)
     * @Assert\Length(max=32)
     */
    protected ?string $timeToMake;

    /**
     * @ORM\Column(type="string", nullable=true, name="LienRealisation", length=255)
     * @Assert\Length(max=255)
     * @Assert\Url()
     */
    protected ?string $link;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Main\CV\RealisationImageMiniature", orphanRemoval=true,mappedBy="realisation")
     * @ORM\JoinColumn(nullable=true)
     */
    protected ?RealisationImageMiniature $mainImage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\CV\RealisationImageGallerie", orphanRemoval=true, mappedBy="realisations")
     *
     * @var Collection<RealisationImageGallerie>
     */
    protected Collection $gallery;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->mainImage = null;
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
}
