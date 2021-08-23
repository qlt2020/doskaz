<?php


namespace App\Tasks\ProfileCompletion;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedTasks extends Command
{
    protected static $defaultName = 'app:tasks:seed-profile-completion';
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var ProfileCompletionTaskRepository
     */
    private $profileCompletionTaskRepository;

    public function __construct(Flusher $flusher, Connection $connection, ProfileCompletionTaskRepository $profileCompletionTaskRepository)
    {
        parent::__construct();
        $this->flusher = $flusher;
        $this->connection = $connection;
        $this->profileCompletionTaskRepository = $profileCompletionTaskRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('users')
            ->andWhere('NOT EXISTS(select 1 from profile_completion_tasks where user_id = users.id)')
            ->execute()
            ->fetchAll();

        foreach ($users as $user) {
            $this->profileCompletionTaskRepository->add(new ProfileCompletionTask($user['id']));
        }

        $this->flusher->flush();
    }
}
