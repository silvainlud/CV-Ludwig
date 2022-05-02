<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\Main\UtilisateurRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Entity(repositoryClass: UtilisateurRepository::class)]
#[HasLifecycleCallbacks]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'CodeUtilisateur', type: 'integer')]
    private int $id;
    #[Column(name: 'Utilisateur', type: 'string', length: 180, unique: true)]
    private string $username;
    #[Column(name: 'EmailUtilisateur', type: 'string', length: 180, unique: true)]
    private string $email;
    #[Column(type: 'json')]
    private array $roles = [];
    /**
     * @var string The hashed password
     */
    #[Column(type: 'string')]
    private string $password;
    #[Column(name: 'DateCreationUtilisateur', type: 'datetime')]
    private DateTimeInterface $date_creation;

    public function getId(): ?int
    {
        if (!isset($this->id)) {
            return null;
        }

        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDateCreation(): DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    #[PrePersist]
    public function setCreatedValue(): void
    {
        $this->date_creation = new DateTime();
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}
