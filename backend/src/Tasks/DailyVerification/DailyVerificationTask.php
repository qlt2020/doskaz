<?php


namespace App\Tasks\DailyVerification;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="daily_verification_tasks")
 */
class DailyVerificationTask implements EventProducer
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
     * @var UuidInterface
     * @ORM\Column(type="uuid", nullable=true)
     */
    private $objectId;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $completedAt;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $reward = 2;

    public function __construct(int $userId)
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->id = Uuid::uuid4();
        $this->userId = $userId;
    }

    public function complete(UuidInterface $objectId)
    {
        if ($this->objectId) {
            return;
        }
        $this->objectId = $objectId;
        $this->completedAt = new \DateTimeImmutable();
        $this->remember(new DailyVerificationTaskDone($this->id, $this->userId, $this->reward));
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }
}
