<?php


namespace App\Tasks\ProfileCompletion;

use App\Infrastructure\DomainEvents\EventListener;
use App\Users\Security\PhoneAuth\PhoneCredentialsCreated;
use App\Users\UserProfileUpdated;

class AttemptProfileCompletionOnUserProfileUpdate implements EventListener
{
    /**
     * @var ProfileCompletionTaskRepository
     */
    private $profileCompletionTaskRepository;

    /**
     * @var ProfileCompletionDataProvider
     */
    private $completionDataProvider;

    public function __construct(ProfileCompletionTaskRepository $profileCompletionTaskRepository, ProfileCompletionDataProvider $completionDataProvider)
    {
        $this->profileCompletionTaskRepository = $profileCompletionTaskRepository;
        $this->completionDataProvider = $completionDataProvider;
    }

    /**
     * @param $event PhoneCredentialsCreated|UserProfileUpdated
     */
    public function handle($event)
    {
        $data = $this->completionDataProvider->forUser($event->userId);
        $this->profileCompletionTaskRepository->forAggregate($event->userId, function (ProfileCompletionTask $task) use ($data) {
            $task->attemptComplete($data);
        });
    }

    public function supports($event): bool
    {
        return $event instanceof PhoneCredentialsCreated || $event instanceof UserProfileUpdated;
    }
}
