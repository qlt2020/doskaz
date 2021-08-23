<?php


namespace App\Awards;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Objects\MapObjectCreated;
use App\Objects\MapObjectRepository;

class IssueAwardForAddedObjects implements EventListener
{
    /**
     * @var AwardRepository
     */
    private AwardRepository $awardRepository;

    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * @var MapObjectRepository
     */
    private MapObjectRepository $mapObjectRepository;

    public function __construct(AwardRepository $awardRepository, Flusher $flusher, MapObjectRepository $mapObjectRepository)
    {
        $this->awardRepository = $awardRepository;
        $this->flusher = $flusher;
        $this->mapObjectRepository = $mapObjectRepository;
    }

    /**
     * @param $event MapObjectCreated
     */
    public function handle($event)
    {
        $objectsCount = $this->mapObjectRepository->countForUser($event->createdBy);
        switch ($objectsCount) {
            case 3:
                $this->awardRepository->add(Award::bronze($event->createdBy, 'Добавлено 3 объекта'));
                break;
            case 8:
                $this->awardRepository->add(Award::silver($event->createdBy, 'Добавлено 8 объектов'));
                break;
            case 15:
                $this->awardRepository->add(Award::gold($event->createdBy, 'Добавлено 15 объектов'));
                break;
        }
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof MapObjectCreated && !empty($event->createdBy);
    }
}
