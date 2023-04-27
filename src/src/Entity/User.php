<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: "username", columns: ["username"])]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: "username", type: 'string', length: 255, unique: true)]
    #[Assert\Length(
        min: 1,
        max: 20,
        minMessage: 'Your username must be at least {{ limit }} characters long',
        maxMessage: 'Your username cannot be longer than {{ limit }} characters',
    )]
    private string $username;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(length: 30)]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Your firstName must be at least {{ limit }} characters long',
        maxMessage: 'Your firstName cannot be longer than {{ limit }} characters',
    )]
    private string $firstName;

    #[ORM\Column(length: 30)]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Your lastName must be at least {{ limit }} characters long',
        maxMessage: 'Your username cannot be longer than {{ limit }} characters',
    )]
    private string $lastName;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
    private string $email;

    #[ORM\Column(length: 15)]
    #[Assert\Regex(pattern: "/^(\+\d{7,15})$/",
        message:"The phone number need to start with sign + and have to have length between 7-15 digits")]
    private string $phone;

    #[ORM\Column(length: 16)]
    private string $roles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = trim($username);

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = trim($firstName);

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = trim($lastName);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = trim($email);

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = trim($phone);

        return $this;
    }

    public function getRoles(): array
    {
        $roles[] = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username ?? '';
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $hashPassword)
    {
        return $this->password = trim($hashPassword);
    }
}
