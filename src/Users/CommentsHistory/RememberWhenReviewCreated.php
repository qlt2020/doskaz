<?php


namespace App\Users\CommentsHistory;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\Reviews\Event\ReviewCreated;

class RememberWhenReviewCreated implements EventListener
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
     * @param $event ReviewCreated
     */
    public function handle($event)
    {
        $this->repository->add(CommentHistory::forObject($event->id, $event->userId));
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof ReviewCreated;
    }
}
