<?php

namespace App\Entity\Main\SilvainEu;

use App\Repository\Main\SilvainEu\ServiceRepository;
use App\Twig\Cache\CacheableInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[UniqueEntity(fields: "name")]
#[Entity(repositoryClass: ServiceRepository::class)]
#[Table(name: 'SilvainEu_Service')]
#[HasLifecycleCallbacks]
class Service implements CacheableInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;
    #[Column(name: 'NomService', type: 'string', length: 32, unique: true)]
    #[Length(max: 32)]
    #[NotBlank]
    private string $name;
    #[Column(name: 'DescriptionService', type: 'text')]
    #[NotBlank]
    private string $description;
    /**
     * @Assert\AtLeastOneOf(constraints={
     *     @Assert\Url(relativeProtocol=true),
     *     @Assert\Regex(pattern="/^\/.*$/")
     * })
     */
    #[Column(name: 'LinkService', type: 'string', length: 128)]
    #[NotBlank]
    private string $link;
    /**
     *
     * @var resource|string
     */
    #[Column(name: 'ImageService', type: 'blob')]
    #[NotNull]
    private $image;
    #[Column(name: 'ImageExtensionService', type: 'string', length: 10)]
    #[Length(max: 10)]
    #[NotBlank]
    private string $imageExtension;
    #[Column(name: 'SlugService', type: 'string', length: 128)]
    #[Length(max: 128)]
    private string $slug;
    #[Column(name: 'ServiceEdition', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $dateModified;

    public function __construct()
    {
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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

    public function getSlugOrNull(): ?string
    {
        if (!isset($this->slug)) {
            return null;
        }

        return $this->slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function MakeSlug(SluggerInterface $slugger): void
    {
        $this->slug = (string)$slugger->slug($this->name)->lower();
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
