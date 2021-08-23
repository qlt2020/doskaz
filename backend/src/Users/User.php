<?php


namespace App\Users;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements EventProducer
{
    use ProducesEvents;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var FullName|null
     * @ORM\Column(type=App\Users\FullName::class, options={"jsonb" = true}, nullable=true)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="json", options={"jsonb": true})
     */
    private $roles = [];

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $status = null;

    public function __construct(?FullName $fullName, ?string $email = null)
    {
        $this->name = '';
        $this->fullName = $fullName ?? new FullName();
        $this->email = $email;
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function update(UserData $data)
    {
        $this->name = $data->name;
        $this->fullName = FullName::parseFromString($data->name);
        $this->roles = $data->roles;
    }

    public function updateProfile(FullName $fullName, ?string $email, ?string $status)
    {
        $this->fullName = $fullName;
        $this->email = empty($email) ? null : $email;
        $this->updatedAt = new \DateTimeImmutable();
        $this->status = $status;
        $this->remember(new UserProfileUpdated($this->id));
    }

    public function migrateFullName()
    {
        $this->fullName = FullName::parseFromString($this->name);
    }

    public function changeAvatar(string $avatar)
    {
        $this->avatar = $avatar;
    }

    public function id()
    {
        return $this->id;
    }

    public function removeAvatar()
    {
        $this->avatar = null;
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @ORM\PostPersist()
     */
    public function fireRegisteredEvent()
    {
        $this->remember(new UserRegistered($this->id));
    }
}
