<?php

namespace App\Entity\Main\SilvainEu;

use App\Repository\Main\SilvainEu\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(name="SilvainEu_Service")
 * @UniqueEntity(fields={"name"})
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true, name="NomService")
     * @Assert\Length(max="32")
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @ORM\Column(type="text", name="DescriptionService")
     * @Assert\NotBlank
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=128, name="LinkService")
     * @Assert\NotBlank
     * @Assert\AtLeastOneOf(constraints={
     *     @Assert\Url(relativeProtocol=true),
     *     @Assert\Regex(pattern="/^\/.*$/")
     * })
     */
    private string $link;

    /**
     * @ORM\Column(type="blob", name="ImageService")
     * @Assert\NotNull
     *
     * @var resource|string
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=10, name="ImageExtensionService")
     * @Assert\Length(max="10")
     * @Assert\NotBlank
     */
    private string $imageExtension;

    /**
     * @ORM\Column(type="string", length=128, name="SlugService")
     * @Assert\Length(max="128")
     */
    private string $slug;

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
        $this->slug = (string) $slugger->slug($this->name)->lower();
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
}
