<?php


namespace App\Blog\Comments;

use Ramsey\Uuid\UuidInterface;

class CommentCreated
{
    /**
     * @var UuidInterface
     */
    public $id;

    /**
     * @var int
     */
    public $userId;

    /**
     * CommentCreated constructor.
     * @param UuidInterface $id
     * @param int $userId
     */
    public function __construct(UuidInterface $id, int $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }
}
