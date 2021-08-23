<?php


namespace App\Tasks\Daily;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="daily_tasks")
 */
class DailyTask implements EventProducer
{
    use ProducesEvents;

    /**
     * @var UuidInterface
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $completedAt;

    /**
     * @var UuidInterface|null
     * @ORM\Column(type="uuid", nullable=true)
     */
    private $objectId;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", options={"default" = 4})
     */
    private $reward = 4;

    public function __construct(int $userId)
    {
        $this->id = Uuid::uuid4();
        $this->userId = $userId;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function objectAdded(UuidInterface $objectId)
    {
        if ($this->objectId) {
            return;
        }
        $this->objectId = $objectId;
        $this->completedAt = new \DateTimeImmutable();
        $this->remember(new DailyTaskDone($this->id, $this->userId, $this->reward));
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }
}
