<?php

namespace App\Users\Subscribe;

use App\Notification\SubscribeData;
use App\Users\User;
use Doctrine\ORM\Mapping as ORM;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ODM()
 * @ORM\Entity()
 * @ORM\Table(name="user_subscribes")
 */
class UserSubscribe
{
    const STATUS_DISABLE = 0;
    const STATUS_ACTIVE = 1;

    /**
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Users\User")
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var SubscribeData
     * @ORM\Column(type=SubscribeData::class, options={"jsonb" = true})
     */
    private $data;

    /**
     * @var int
     * @ORM\Column(type="integer", name="status_id", options={"default" = 0})
     */
    private $statusId;

    public function __construct(User $user, SubscribeData $data, int $statusId)
    {
        $this->user = $user;
        $this->data = $data;
        $this->statusId = $statusId;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return SubscribeData
     */
    public function getData(): SubscribeData
    {
        return $this->data;
    }

    /**
     * @param SubscribeData $data
     */
    public function setData(SubscribeData $data): void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @param int $statusId
     */
    public function setStatusId(int $statusId): void
    {
        $this->statusId = $statusId;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

}