<?php


namespace App\Objects\EventsHistory;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="objects_events_history")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $objectId;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type=App\Objects\EventsHistory\EventData::class, options={"jsonb" = true})
     */
    private $data;

    public function __construct(int $objectId, int $userId, EventData $data)
    {
        $this->id = Uuid::uuid4();
        $this->date = new \DateTimeImmutable();
        $this->objectId = $objectId;
        $this->data = $data;
        $this->userId = $userId;
    }
}
