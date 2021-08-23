<?php


namespace App\Objects\Command;

use App\Objects\AttributesConfiguration;
use App\Objects\Category;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\Loader\CsvFileLoader;
use Symfony\Contracts\Translation\TranslatorInterface;

class DebugTranslations extends Command
{
    protected static $defaultName = 'app:objects:debug-translations';
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;
    /**
     * @var CsvFileLoader
     */
    private CsvFileLoader $csvFileLoader;

    public function __construct(TranslatorInterface $translator, CsvFileLoader $csvFileLoader)
    {
        parent::__construct();
        $this->translator = $translator;
        $this->csvFileLoader = $csvFileLoader;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->csvFileLoader->setCsvControl(',');
        array_walk_recursive(AttributesConfiguration::$configuration, function (&$val) {
            if (is_string($val)) {
                $val = $this->translator->trans($val, [], 'attributes', 'kz');
                dd($val);
            }
        });

        dd(AttributesConfiguration::$configuration);
    }
}
