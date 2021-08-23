<?php


namespace App\Objects;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCategories extends Command
{
    protected static $defaultName = 'app:objects:import-categories';

    private $sourceConnection;

    private $destinationConnection;

    public function __construct(Connection $sourceConnection, Connection $destinationConnection)
    {
        $this->sourceConnection = $sourceConnection;
        $this->destinationConnection = $destinationConnection;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $count = $this->destinationConnection->executeQuery('SELECT COUNT(*) FROM object_categories')->fetchColumn();
        if ($count > 0) {
            return;
        }

        $data = $this->sourceConnection->createQueryBuilder()
            ->select('*')->from('categories')
            ->execute()->fetchAll();

        $iconsRemap = [
            'fa-shield' => 'fa-shield-alt',
            'fa-building-o' => 'fa-building',
            'fa-money' => 'fa-money-bill-alt',
            'fa-pencil-square-o' => 'fa-pen-square',
            'fa-scissors' => 'fa-cut',
            'fa-video-camera' => 'fa-video',
            'fa-diamond' => 'fa-gem',
            'fa-spoon' => 'fa-utensil-spoon',
            'fa-cutlery' => 'fa-utensils',
            'fa-ticket' => 'fa-ticket-alt',
            'fa-product-hunt' => 'fa-product-hunt',
            'fa-futbol-o' => 'fa-futbol',
            'fa-bar-chart' => 'fa-chart-bar',
            'fa-credit-card-alt' => 'fa-credit-card',
            'fa-sun-o' => 'fa-sun',
            'fa-moon-o' => 'fa-moon',
        ];


        foreach ($data as $category) {
            $this->destinationConnection->insert('object_categories', [
                'id' => $category['id'],
                'title' => $category['title_ru'],
                'parent_id' => $category['parent_id'],
                'icon' => $iconsRemap[$category['icon']] ?? $category['icon']
            ]);
        }

        $this->destinationConnection->exec('SELECT setval(\'object_categories_id_seq\', (select max(id) from object_categories), true)');
    }
}
