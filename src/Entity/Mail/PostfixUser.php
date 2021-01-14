<?php

namespace App\Entity\Mail;

use App\Repository\Mail\PostfixUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostfixUserRepository::class)
 * @ORM\Table(name="user")
 */
class PostfixUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
