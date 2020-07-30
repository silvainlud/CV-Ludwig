<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\TechnologieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TechnologieRepository::class)
 * @ORM\Table(name="CV_Technologie")
 */
class Technologie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="NumTechnologie")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true, name="NomTechnologie")
     * @Assert\NotBlank
     * @Assert\Length(max="64")
     */
    private $name;

    /**
     * @ORM\Column(type="text", name="DescriptionTechnologie")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="binary", name="ImageTechnologie")
     * @Assert\NotNull
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=10, name="ImageExtensionTechnologie")
     * @Assert\NotBlank
     * @Assert\Length(max="10")
     */
    private $imageExtension;

    /**
     * @ORM\Column(type="string", length=15, name="ColorTechnologie", nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(max="25")
     */
    private $color;

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

    public function displayPhoto()
    {
        if (null === $this->rawPhoto) {
            $this->rawPhoto = 'data:image/png;base64,' . base64_encode(stream_get_contents($this->getPhoto()));
        }

        return $this->rawPhoto;
    }

    public function displayImage()
    {
        if (null != $this->image && null != $this->imageExtension) {
            return 'data:image/' . $this->imageExtension . ';base64,' . base64_encode(stream_get_contents($this->image));
        }

        return $this->image;
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
}
