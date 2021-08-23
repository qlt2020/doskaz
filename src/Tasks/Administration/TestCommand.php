<?php


namespace App\Tasks\Administration;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    /**
     * @var TaskForObjectFinder
     */
    private TaskForObjectFinder $taskForObjectFinder;

    public function __construct(TaskForObjectFinder $taskForObjectFinder)
    {
        parent::__construct();
        $this->taskForObjectFinder = $taskForObjectFinder;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        dd($this->taskForObjectFinder->find(Uuid::fromString('83f00e33-857e-4246-b9ac-1003275b079b')));
    }
}
