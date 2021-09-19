<?php


namespace App\Tasks\ProfileCompletion;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\DomainEvents\EventListener;
use App\Users\UserRegistered;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class CreateProfileCompletionTaskOnUserRegistered implements EventListener
{
    /**
     * @var ProfileCompletionTaskRepository
     */
    private $profileCompletionTaskRepository;

    /**
     * @var Flusher
     */
    private $flusher;

    private $logger;

    public function __construct(ProfileCompletionTaskRepository $profileCompletionTaskRepository, Flusher $flusher, LoggerInterface $logger)
    {
        $this->profileCompletionTaskRepository = $profileCompletionTaskRepository;
        $this->flusher = $flusher;
        $this->logger = $logger;
    }

    /**
     * @param $event UserRegistered
     */
    public function handle($event)
    {
        $task = new ProfileCompletionTask($event->id);
        $this->profileCompletionTaskRepository->add($task);
        $this->flusher->flush();
    }

    public function supports($event): bool
    {
        return $event instanceof UserRegistered;
    }
}
