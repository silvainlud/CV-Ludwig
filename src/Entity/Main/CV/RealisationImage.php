<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use DateTimeInterface;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use DateTime;
use App\Twig\Cache\CacheableInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

/**
 * Class RealisationImage.
 */
#[Table(name: 'CV_RealisationImage')]
#[Entity]
#[InheritanceType(value: 'SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap(['miniature' => RealisationImageMiniature::class, 'gallery' => RealisationImageGallerie::class])]
#[HasLifecycleCallbacks]
abstract class RealisationImage implements CacheableInterface
{
    #[Id]
    #[Column(type: 'string')]
    protected string $id;
    /**
     * @var resource|string $image
     */
    #[Column(type: 'blob', nullable: false)]
    protected $image;
    #[Column(name: 'RealisationImageEdition', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTimeInterface $dateModified;

    public function __construct()
    {
        $this->id = Uuid::v6()->toRfc4122();
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
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

    /**
     * @return resource|string|null
     */
    public function getImageOrNull()
    {
        return $this->image;
    }

    public function getUpload(): ?UploadedFile
    {
        return null;
    }

    public function setUpload(UploadedFile $file): self
    {
        if (!empty($file->getPath())) {
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

    abstract public function postUpdate(): void;
}
