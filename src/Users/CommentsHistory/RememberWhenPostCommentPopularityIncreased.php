<?php


namespace App\Users\CommentsHistory;

use App\Blog\Comments\PopularityIncreased;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;

class RememberWhenPostCommentPopularityIncreased implements EventListener
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
     * @param $event PopularityIncreased
     */
    public function handle($event)
    {
        $historyItem = $this->repository->find($event->id);
        $historyItem->increasePopularity();
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof PopularityIncreased;
    }
}
