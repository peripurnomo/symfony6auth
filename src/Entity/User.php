<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Table(name: '`users`')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'E-mail has been registered!')]
#[UniqueEntity(fields: ['username'], message: 'Username has been registered!')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true, nullable: false)]
    #[
        Assert\NotBlank,
        Assert\Length(max: 32),
        Assert\Email
    ]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private ?array $roles = [];

    #[ORM\Column(type: 'string', nullable: false)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 8, max: 4096),
    ]
    private ?string $password = null;

    #[ORM\Column(type: 'string', length: 255, unique: true, nullable: false)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 2, max: 32),
        Assert\Regex(pattern: '/^[a-z0-9]+$/i', htmlPattern: '^[a-zA-Z0-9]+$')
    ]
    private ?string $username = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    #[
        Assert\NotBlank,
        Assert\Length(min: 2, max: 32),
        Assert\Regex(pattern: '/^[a-z A-Z]+$/i', htmlPattern: '^[a-z A-Z]+$')
    ]
    private ?string $fullname = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isVerified = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $joinAt = null;

    public function __construct()
    {
        $this->joinAt = new \DateTimeImmutable('NOW');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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
     * @see PasswordAuthenticatedUserInterface
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getJoinAt(): ?\DateTimeImmutable
    {
        return $this->joinAt;
    }

    public function setJoinAt(\DateTimeImmutable $joinAt): self
    {
        $this->joinAt = $joinAt;

        return $this;
    }
}
