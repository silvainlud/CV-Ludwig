<?php

namespace App\Entity\Main\CV;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Repository\Main\CV\CompetenceNiveauRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: CompetenceNiveauRepository::class)]
#[Table(name: 'CV_CompetenceNiveau')]
class CompetenceNiveau
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;
    #[Column(name: 'NomNiveau', type: 'string', length: 32)]
    #[Length(max: 32)]
    #[NotBlank]
    private string $name;
    #[Column(name: 'ClassNiveau', type: 'string', length: 10)]
    #[Length(max: 10)]
    #[NotBlank]
    private string $class;
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
    public function getClass(): string
    {
        return $this->class;
    }
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
}
