<?php declare(strict_types = 1);

namespace App\Entity\User;

use App\Model\DoctrineTraits\DefaultEnabledTrait;
use App\Model\DoctrineTraits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"email"}),
 *     },
 *     indexes={
 *         @ORM\Index(columns={"firstname"}),
 *         @ORM\Index(columns={"lastname"}),
 *         @ORM\Index(columns={"is_enabled"}),
 *     },
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    use DefaultEnabledTrait;
    use TimestampableTrait;

    public const PASSWORD_LENGTH_MIN = 8;
    public const PASSWORD_LENGTH_MAX = Argon2iPasswordEncoder::MAX_PASSWORD_LENGTH;

    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="uuid", name="user_id")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(name="firstname", type="string", nullable=true)
     */
    protected $firstname;

    /**
     * @var string|null
     * @ORM\Column(name="lastname", type="string", nullable=true)
     */
    protected $lastname;

    /**
     * @var string|null
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * @var string|null
     * @ORM\Column(name="password", type="string")
     */
    protected $password;

    /** @var string|null */
    protected $plainPassword;

    /**
     * @var array
     * @ORM\Column(name="roles", type="json_array", nullable=true)
     */
    protected $roles = [
        'ROLE_USER',
    ];

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function __toString(): string
    {
        if ($this->getFirstname() || $this->getLastname()) {
            return trim(sprintf(
                '%s %s (%s)',
                $this->getFirstname(),
                $this->getLastname(),
                $this->getEmail()
            ));
        }

        return (string) $this->getEmail();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), true);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getSalt(): string
    {
        return '';
    }

    public function getUsername(): string
    {
        return (string) $this->getEmail();
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
