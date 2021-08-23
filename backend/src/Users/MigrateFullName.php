<?php


namespace App\Users;

use App\Infrastructure\Doctrine\Flusher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateFullName extends Command
{
    protected static $defaultName = 'app:users:migrate-name';
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserRepository $userRepository, Flusher $flusher)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->flusher = $flusher;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->userRepository->findAll() as $user) {
            $user->migrateFullName();
        }

        $this->flusher->flush();
    }
}
