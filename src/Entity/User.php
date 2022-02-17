<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"email"}, message="L'email est déjà utilisé")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $username;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir une adresse mail')]
    #[Assert\Email(message: "L'email n'est pas valide.")]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'Veuillez saisir un mot de passe')]
    #[Assert\Regex(pattern: "/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{6,}$/", message: "")]
    private string $password;

    #[ORM\Column(type: 'datetime_immutable')]
    private $registered_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $accountMustBeVerifiedBefore;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $registrationToken;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isVerified;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $accountVerified_at;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $forgotPasswordToken;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $forgotPasswordTokenRequested_at;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $forgotPasswordTokenMustBeVerifiedBefore;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $forgotPasswordTokenVerified_at;

    public function __construct()
    {
        $this->registered_at = new \DateTimeImmutable('now');
        $this->isVerified = false;
        $this->roles = ['ROLE_USER'];
        $this->accountMustBeVerifiedBefore = (new \DateTimeImmutable('now'))->add(new \DateInterval("P1D"));

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
        return $this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
        return $this->roles;
    }

    public function setRoles($roles): self
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


    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registered_at;
    }

    public function setRegisteredAt($registered_at): self
    {
        $this->registered_at = $registered_at;
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
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

    /**
     * @return string|null
     */
    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?string $registrationToken): self
    {
        $this->registrationToken = $registrationToken;
        return $this;
    }

    public function getAccountMustBeVerifiedBefore(): ?\DateTimeImmutable
    {
        return $this->accountMustBeVerifiedBefore;
    }

    public function setAccountMustBeVerifiedBefore($accountMustBeVerifiedBefore): void
    {
        $this->accountMustBeVerifiedBefore = $accountMustBeVerifiedBefore;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getAccountVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->accountVerified_at;
    }

    public function setAccountVerifiedAt($accountVerified_at): self
    {
        $this->accountVerified_at = $accountVerified_at;

        return $this;
    }

    public function getForgotPasswordToken(): ?string
    {
        return $this->forgotPasswordToken;
    }

    public function setForgotPasswordToken(?string $forgotPasswordToken): self
    {
        $this->forgotPasswordToken = $forgotPasswordToken;

        return $this;
    }

    public function getForgotPasswordTokenRequestedAt(): ?\DateTimeImmutable
    {
        return $this->forgotPasswordTokenRequested_at;
    }

    public function setForgotPasswordTokenRequestedAt($forgotPasswordTokenRequested_at): self
    {
        $this->forgotPasswordTokenRequested_at = $forgotPasswordTokenRequested_at;

        return $this;
    }

    public function getForgotPasswordTokenMustBeVerifiedBefore(): ?\DateTimeImmutable
    {
        return $this->forgotPasswordTokenMustBeVerifiedBefore;
    }

    public function setForgotPasswordTokenMustBeVerifiedBefore($forgotPasswordTokenMustBeVerifiedBefore): self
    {
        $this->forgotPasswordTokenMustBeVerifiedBefore = $forgotPasswordTokenMustBeVerifiedBefore;

        return $this;
    }

    public function getForgotPasswordTokenVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->forgotPasswordTokenVerified_at;
    }

    public function setForgotPasswordTokenVerifiedAt($forgotPasswordTokenVerified_at): self
    {
        $this->forgotPasswordTokenVerified_at = $forgotPasswordTokenVerified_at;

        return $this;
    }
}