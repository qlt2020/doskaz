<?php


namespace App\Users\CommentsHistory;

use App\Blog\Comments\CommentCreated;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;

class RememberWhenPostCommentCreated implements EventListener
{
    /**
     * @var CommentHistoryRepository
     */
    private $repository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(CommentHistoryRepository $repository, Flusher $flusher)
    {
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    /**
     * @param $event CommentCreated
     */
    public function handle($event)
    {
        $this->repository->add(CommentHistory::forPost($event->id, $event->userId));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof CommentCreated;
    }
}
