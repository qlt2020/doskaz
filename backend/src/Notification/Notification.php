<?php

namespace App\Notification;

use App\Infrastructure\DomainEvents\EventProducer;
use App\Infrastructure\DomainEvents\ProducesEvents;
use App\Users\User;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations\Schema;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @Schema(title="Уведомление", schema="Notification")
 * @ORM\Entity()
 * @ORM\Table(name="notifications")
 */
class Notification implements EventProducer
{
    use ProducesEvents;

    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Users\User")
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="json", options={"jsonb" = true})
     */
    private $data;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $closedAt;


    public function __construct(User $user, $data)
    {
        $this->id = Uuid::uuid4();
        $this->user = $user;
        $this->data = $data;
        $this->createdAt = new \DateTimeImmutable();
    }

}