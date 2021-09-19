<?php


namespace App\Tasks\ProfileCompletion;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="profile_completion_tasks")
 */
class ProfileCompletionTask implements EventProducer
{
    use ProducesEvents;

    private const REWARD_POINTS_COUNT = 3;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $progress = 0;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $completedAt;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->progress = 0;
    }

    public function attemptComplete(ProfileCompletionTaskData $data)
    {
        if ($this->completedAt) {
            return;
        }
        $completedFields = array_filter($data->toArray(), function ($item) {
            return !empty($item);
        });

        $this->progress = round(count($completedFields) / count($data->toArray()) * 100);
        if ($this->progress === 100.0) {
            $this->completedAt = new \DateTimeImmutable();
            $this->remember(new ProfileCompletionTaskDone($this->userId, self::REWARD_POINTS_COUNT));
        }
    }

    public function isCompleted(): bool
    {
        return !is_null($this->completedAt);
    }
}
