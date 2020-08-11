<?php

namespace App\Entity\Main\SilvainEu;

use App\Repository\Main\SilvainEu\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(name="SilvainEu_Service")
 */
class Service
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true, name="NomService")
     * @Assert\Length(max="32")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="text", name="DescriptionService")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128, name="LinkService")
     * @Assert\NotBlank
     * @Assert\Url
     */
    private $link;

    /**
     * @ORM\Column(type="blob", name="ImageService")
     * @Assert\NotNull
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=10, name="ImageExtensionService")
     * @Assert\Length(max="10")
     * @Assert\NotBlank
     */
    private $imageExtension;

    /**
     * @ORM\Column(type="string", length=128, name="SlugService")
     * @Assert\Length(max="128")
     */
    private $slug;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageExtension(): ?string
    {
        return $this->imageExtension;
    }

    public function setImageExtension(string $imageExtension): self
    {
        $this->imageExtension = $imageExtension;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function MakeSlug(SluggerInterface $slugger)
    {
        $this->slug = (string) $slugger->slug($this->name)->lower();
    }

    public function getUpload()
    {
        return null;
    }

    public function setUpload(UploadedFile $file): self
    {
        if (!empty($file->getPath())) {
            $this->imageExtension = $file->guessExtension();
            $this->image = file_get_contents($file);
        }

        return $this;
    }
}
