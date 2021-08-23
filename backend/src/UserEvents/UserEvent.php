<?php


namespace App\UserEvents;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_events")
 */
class UserEvent
{
    /**
     * @var UuidInterface
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $date;

    /**
     * @var Data
     * @ORM\Column(type=App\UserEvents\Data::class, options={"jsonb" = true})
     */
    private $data;

    /**
     * UserEvent constructor.
     * @param int $userId
     * @param Data $data
     * @throws \Exception
     */
    public function __construct(int $userId, Data $data)
    {
        $this->id = Uuid::uuid4();
        $this->userId = $userId;
        $this->data = $data;
        $this->date = new \DateTimeImmutable();
    }
}
