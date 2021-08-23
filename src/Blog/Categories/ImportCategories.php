<?php
declare(strict_types=1);

namespace App\Blog\Categories;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportCategories extends Command
{
    protected static $defaultName = 'app:blogCategories:import';

    private $source;

    private $destination;

    public function __construct(Connection $source, Connection $destination)
    {
        $this->source = $source;
        $this->destination = $destination;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->source->createQueryBuilder()
            ->select('*')->from('types')
            ->execute()->fetchAll();

        foreach ($items as $item) {
            $this->destination->insert('blog_categories', [
                'id' => $item['id'],
                'title' => $item['title_ru'],
                'slug_value' => $item['sef'],
                'created_at' => $this->destination->convertToDatabaseValue(new \DateTimeImmutable(), 'datetimetz_immutable'),
                'updated_at' => $this->destination->convertToDatabaseValue(new \DateTimeImmutable(), 'datetimetz_immutable')
            ]);
        }
        $this->destination->exec('SELECT setval(\'blog_categories_id_seq\', (select max(id) from blog_categories), true)');
    }
}
