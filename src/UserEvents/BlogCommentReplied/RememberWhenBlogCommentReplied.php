<?php


namespace App\UserEvents\BlogCommentReplied;

use App\Blog\Comments\CommentRepository;
use App\Blog\Comments\ReplyCreated;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\UserEvents\UserEvent;
use App\UserEvents\UserEventRepository;

class RememberWhenBlogCommentReplied implements EventListener
{
    /**
     * @var UserEventRepository
     */
    private $userEventRepository;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserEventRepository $userEventRepository, CommentRepository $commentRepository, Flusher $flusher)
    {
        $this->userEventRepository = $userEventRepository;
        $this->commentRepository = $commentRepository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event ReplyCreated
     * @throws \Exception
     */
    public function handle($event)
    {
        $parentComment = $this->commentRepository->find($event->parentId);
        if ($parentComment->userId() === $event->userId) {
            return;
        }

        $this->userEventRepository->add(new UserEvent($parentComment->userId(), new BlogCommentRepliedData($event->parentId, $event->userId, $event->id)));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof ReplyCreated;
    }
}
