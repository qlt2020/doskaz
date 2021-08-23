<?php


namespace App\Users\CommentsHistory;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_comments_history")
 */
class CommentHistory
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $date;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default" = 0})
     */
    private $popularity = 0;

    private const TYPE_OBJECT = 'object';
    private const TYPE_POST = 'post';

    public static function forPost(UuidInterface $id, int $userId): self
    {
        $self = new self();
        $self->id = $id;
        $self->type = self::TYPE_POST;
        $self->userId = $userId;
        $self->date = new \DateTimeImmutable();
        return $self;
    }

    public static function forObject(UuidInterface $id, int $userId): self
    {
        $self = new self();
        $self->id = $id;
        $self->type = self::TYPE_OBJECT;
        $self->userId = $userId;
        $self->date = new \DateTimeImmutable();
        return $self;
    }

    public function increasePopularity()
    {
        $this->popularity++;
    }
}
