<?php


namespace App\UserEvents\BlogCommentReplied;

use App\UserEvents\Data;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ODM()
 */
class BlogCommentRepliedData extends Data
{
    /**
     * @var UuidInterface id комментария
     */
    public $replyId;

    /**
     * @var UuidInterface id комментария на который ответ
     */
    public $commentId;

    /**
     * @var int
     */
    public $userId;

    /**
     * BlogCommentReplied constructor.
     * @param UuidInterface $commentId
     * @param int $userId
     * @param UuidInterface $replyId
     */
    public function __construct(UuidInterface $commentId, int $userId, UuidInterface $replyId)
    {
        $this->commentId = $commentId;
        $this->userId = $userId;
        $this->replyId = $replyId;
    }
}
