<?php


namespace App\Tasks\Administration;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_administration_tasks")
 */
class UserAdministrationTask implements EventProducer
{
    use ProducesEvents;

    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $taskId;

    /**
     * @ORM\Column(type="integer")
     */
    private int $points;

    /**
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $objectId;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $createdAt;

    public function __construct(int $userId, UuidInterface $taskId, int $points, UuidInterface $objectId)
    {
        $this->id = Uuid::uuid4();
        $this->userId = $userId;
        $this->taskId = $taskId;
        $this->points = $points;
        $this->objectId = $objectId;
        $this->createdAt = new \DateTimeImmutable();
        $this->remember(new AdministrationTaskDone($this->taskId, $this->userId, $this->points));
    }
}
