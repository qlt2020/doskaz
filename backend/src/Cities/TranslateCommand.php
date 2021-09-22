<?php


namespace App\Cities;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TranslateCommand extends Command
{
    protected static $defaultName = 'app:cities:translate';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $citiesData = array_combine(array_column(Cities::list(), 'id'), array_column(Cities::list(), 'nameKz'));
        $citiesDataEn = array_combine(array_column(Cities::list(), 'id'),
            array_column(Cities::list(), 'nameEn'));
        /**
         * @var $city Cities
         */
        foreach ($this->entityManager->getRepository(Cities::class)->findAll() as $city) {
            $city->setTranslatableLocale('kz');
            $city->setName($citiesData[$city->getId()]);
        }

        foreach ($this->entityManager->getRepository(Cities::class)->findAll() as $city) {
            $city->setTranslatableLocale('en');
            $city->setName($citiesDataEn[$city->getId()]);
        }

        $this->entityManager->flush();
    }
}
