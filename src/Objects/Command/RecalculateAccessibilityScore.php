<?php


namespace App\Objects\Command;

use App\Infrastructure\Doctrine\Flusher;
use App\Objects\MapObjectRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecalculateAccessibilityScore extends Command
{
    protected static $defaultName = 'app:objects:recalculate-accessibility-score';

    /**
     * @var MapObjectRepository
     */
    private MapObjectRepository $repository;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    public function __construct(MapObjectRepository $repository, Flusher $flusher)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->flusher = $flusher;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->repository->findAll() as $item) {
            $item->recalculateAccessibilityScore();
        }
        $this->flusher->flush();
    }
}
