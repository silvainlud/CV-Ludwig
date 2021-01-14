<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\TechnologieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TechnologieRepository::class)
 * @ORM\Table(name="CV_Technologie")
 * @UniqueEntity(fields={"name"})
 */
class Technologie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="NumTechnologie")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true, name="NomTechnologie")
     * @Assert\NotBlank
     * @Assert\Length(max="64")
     */
    private string $name;

    /**
     * @ORM\Column(type="text", name="DescriptionTechnologie")
     * @Assert\NotBlank
     */
    private string $description;

    /**
     * @ORM\Column(type="blob", name="ImageTechnologie")
     * @Assert\NotNull
     *
     * @var resource|string
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=10, name="ImageExtensionTechnologie")
     * @Assert\NotBlank
     * @Assert\Length(max="10")
     */
    private string $imageExtension;

    /**
     * @ORM\Column(type="string", length=25, name="ColorTechnologie", nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(max="25")
     */
    private string $color;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private string $slug;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
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
     *
     * @return $this
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
        if (isset($this->image, $this->imageExtension)) {
            if (!\is_string($this->image)) {
                $_f = stream_get_contents($this->image);
                if (false === $_f) {
                    return null;
                }
            } else {
                $_f = $this->image;
            }

            return 'data:image/' . $this->imageExtension . ';base64,' . base64_encode($_f);
        }

        return null;
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
            $this->slug = (string) $slugger->slug($this->getId() . ' ' . $this->getName())->lower();
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
}
