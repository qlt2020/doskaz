<?php


namespace App\Levels;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedLevels extends Command
{
    protected static $defaultName = 'app:levels:seed';

    private Flusher $flusher;

    private Connection $connection;

    private LevelRepository $levelRepository;

    public function __construct(Flusher $flusher, Connection $connection, LevelRepository $levelRepository)
    {
        parent::__construct();
        $this->flusher = $flusher;
        $this->connection = $connection;
        $this->levelRepository = $levelRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('users')
            ->andWhere('NOT EXISTS(select 1 from levels where user_id = users.id)')
            ->execute()
            ->fetchAll();

        foreach ($users as $user) {
            $this->levelRepository->add(new Level($user['id']));
        }

        $this->flusher->flush();
    }
}
