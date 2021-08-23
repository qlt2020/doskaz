<?php


namespace App\Complaints;

use App\Infrastructure\Doctrine\Flusher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedComplaintAuthorities extends Command
{
    protected static $defaultName = 'app:complaints:seed-authorities';

    private static $authorities = [
        'Министерство здравоохранения РК',
        'Департамент комитета труда'
    ];

    private $flusher;

    private $authorityRepository;

    public function __construct(Flusher $flusher, AuthorityRepository $authorityRepository)
    {
        $this->authorityRepository = $authorityRepository;
        $this->flusher = $flusher;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * Skip seeding if authorities exists
         */
        if ($this->authorityRepository->count([]) > 0) {
            return;
        }

        foreach (self::$authorities as $authority) {
            $this->authorityRepository->add(new ComplaintAuthority($authority));
        }
        $this->flusher->flush();
    }
}
