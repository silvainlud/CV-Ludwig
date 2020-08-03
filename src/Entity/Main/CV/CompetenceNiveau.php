<?php

namespace App\Entity\Main\CV;

use App\Repository\Main\CV\CompetenceNiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetenceNiveauRepository::class)
 * @ORM\Table(name="CV_CompetenceNiveau")
 */
class CompetenceNiveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, name="NomNiveau")
     * @Assert\Length(max="32")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10, name="ClassNiveau")
     * @Assert\Length(max="10")
     * @Assert\NotBlank
     */
    private $class;

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

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
}
