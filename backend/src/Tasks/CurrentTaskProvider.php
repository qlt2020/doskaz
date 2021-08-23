<?php


namespace App\Tasks;

use App\Tasks\Daily\DailyTask;
use App\Tasks\Daily\DailyTaskRepository;
use App\Tasks\DailyVerification\DailyVerificationTaskRepository;
use App\Tasks\ProfileCompletion\ProfileCompletionTaskRepository;

class CurrentTaskProvider
{
    /**
     * @var DailyTaskRepository
     */
    private $dailyTaskRepository;
    /**
     * @var ProfileCompletionTaskRepository
     */
    private $profileCompletionTaskRepository;
    /**
     * @var DailyVerificationTaskRepository
     */
    private $dailyVerificationTaskRepository;

    public function __construct(DailyTaskRepository $dailyTaskRepository, ProfileCompletionTaskRepository $profileCompletionTaskRepository, DailyVerificationTaskRepository $dailyVerificationTaskRepository)
    {
        $this->dailyTaskRepository = $dailyTaskRepository;
        $this->profileCompletionTaskRepository = $profileCompletionTaskRepository;
        $this->dailyVerificationTaskRepository = $dailyVerificationTaskRepository;
    }

    /**
     * @param int $userId
     * @return DailyTask|DailyVerification\DailyVerificationTask|ProfileCompletion\ProfileCompletionTask|null
     */
    public function execute(int $userId)
    {
        $profileCompletionTask = $this->profileCompletionTaskRepository->find($userId);
        if (!$profileCompletionTask->isCompleted()) {
            return $profileCompletionTask;
        }

        $dailyVerificationTask = $this->dailyVerificationTaskRepository->findCurrentForUser($userId);

        if ($dailyVerificationTask) {
            return $dailyVerificationTask;
        }

        return $this->dailyTaskRepository->findCurrentForUser($userId);
    }
}
