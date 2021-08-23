<?php


namespace App\Tasks\DailyVerification;

use App\Infrastructure\Doctrine\Flusher;
use App\Tasks\Daily\DailyTask;
use App\Tasks\Daily\DailyTaskRepository;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedDailyVerificationTasks extends Command
{
    protected static $defaultName = 'app:tasks:seed-daily-verification';
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var DailyTaskRepository
     */
    private $dailyVerificationTaskRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(Connection $connection, DailyVerificationTaskRepository $dailyVerificationTaskRepository, Flusher $flusher)
    {
        parent::__construct();
        $this->connection = $connection;
        $this->dailyVerificationTaskRepository = $dailyVerificationTaskRepository;
        $this->flusher = $flusher;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('users')
            ->andWhere('NOT EXISTS (select 1 from daily_verification_tasks where daily_verification_tasks.user_id = users.id)')
            ->execute()
            ->fetchAll();

        foreach ($users as $user) {
            $this->dailyVerificationTaskRepository->add(new DailyVerificationTask($user['id']));
        }
        $this->flusher->flush();
    }
}
