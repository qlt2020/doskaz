<?php


namespace App\ProfileNotifications;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="profile_notifications")
 */
class ProfileNotification
{
    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;

    /**
     * @ORM\Column(type=App\ProfileNotifications\ProfileNotificationData::class, options={"jsonb" = true})
     */
    private ProfileNotificationData $data;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $closedAt;

    /**
     * ProfileNotification constructor.
     * @param int $userId
     * @param ProfileNotificationData $data
     */
    public function __construct(int $userId, ProfileNotificationData $data)
    {
        $this->id = Uuid::uuid4();
        $this->userId = $userId;
        $this->data = $data;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function close()
    {
        $this->closedAt = new \DateTimeImmutable();
    }
}
