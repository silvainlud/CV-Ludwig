<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\TechnologieRepository;
use App\Twig\Cache\CacheableInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InverseJoinColumns;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Url;

#[UniqueEntity(fields: "name")]
#[Entity(repositoryClass: TechnologieRepository::class)]
#[Table(name: 'CV_Technologie')]
#[HasLifecycleCallbacks]
class Technologie implements CacheableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'NumTechnologie', type: 'integer')]
    private int $id;
    #[Column(name: 'NomTechnologie', type: 'string', length: 64, unique: true)]
    #[NotBlank]
    #[Length(max: 64)]
    private string $name;
    #[Column(name: 'DescriptionTechnologie', type: 'text', nullable: true)]
    private ?string $description;
    /**
     *
     * @var resource|string
     */
    #[Column(name: 'ImageTechnologie', type: 'blob')]
    #[NotNull]
    private $image;
    #[Column(name: 'ImageExtensionTechnologie', type: 'string', length: 10)]
    #[NotBlank]
    #[Length(max: 10)]
    private string $imageExtension;
    #[Column(name: 'ColorTechnologie', type: 'string', length: 25, nullable: true)]
    #[NotBlank]
    #[Length(max: 25)]
    private string $color;
    #[Column(type: 'string', length: 255, unique: true, nullable: false)]
    private string $slug;
    /**
     *
     * @var Collection<self> $linkedTechonologies
     */
    #[ManyToMany(targetEntity: 'App\Entity\Main\CV\Technologie')]
    #[JoinTable]
    #[JoinColumn(name: 'source_technologie_num', referencedColumnName: 'NumTechnologie')]
    #[ORM\InverseJoinColumn(name: 'target_technologie_num', referencedColumnName: 'NumTechnologie')]
    #[Expression(expression: 'this.getLinkedTechonologies().contains(this) == false', message: 'cv.skills.technology.attr.link.not-contain-slef')]
    private Collection $linkedTechonologies;
    #[Column(type: 'string', length: 255, nullable: true)]
    #[Url]
    #[Length(max: 255)]
    private ?string $link;
    #[Column(name: 'TechnologieEdition', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $dateModified;

    public function __construct()
    {
        $this->linkedTechonologies = new ArrayCollection();
        $this->link = null;
        $this->dateModified = new DateTime();
        $this->description = null;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return resource|string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param resource|string $image
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageExtension(): string
    {
        return $this->imageExtension;
    }

    public function setImageExtension(string $imageExtension): self
    {
        $this->imageExtension = $imageExtension;

        return $this;
    }

    public function displayImage(): ?string
    {
        try {
            if (!\is_string($this->image)) {
                $_f = stream_get_contents($this->image);
                if (false === $_f) {
                    return null;
                }
            } else {
                $_f = $this->image;
            }

            return 'data:image/' . $this->imageExtension . ';base64,' . base64_encode($_f);
        } catch (\Exception) {
            return null;
        }
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getUpload(): ?UploadedFile
    {
        return null;
    }

    public function setUpload(UploadedFile $file): self
    {
        if (!empty($file->getPath())) {
            $ext = $file->guessExtension();
            if (null !== $ext) {
                $this->imageExtension = $ext;
            }
            $_file = file_get_contents($file);
            if (false !== $_file) {
                $this->image = $_file;
            }
        }

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

    public function getLinkedTechonologies(): Collection
    {
        return $this->linkedTechonologies;
    }

    public function addLinkedTechonologies(self $t): self
    {
        if (!$this->linkedTechonologies->contains($t)) {
            $this->linkedTechonologies->add($t);
        }

        return $this;
    }

    public function removeLinkedTechonologies(self $t): self
    {
        if ($this->linkedTechonologies->contains($t)) {
            $this->linkedTechonologies->removeElement($t);
        }

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
}
