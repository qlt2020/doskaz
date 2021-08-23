<?php
declare(strict_types=1);

namespace App\Blog\Posts;

use App\Blog\Image;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ImportBlogPosts extends Command
{
    protected static $defaultName = 'app:blogPosts:import';

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
        $sourcePosts = $this->source->createQueryBuilder()
            ->addSelect('materials.id')
            ->addSelect('materials.title')
            ->addSelect('materials.sef')
            ->addSelect('materials.type_id')
            ->addSelect('materials.date')
            ->addSelect('materials.is_published')
            ->addSelect('materials.annotation')
            ->addSelect('materials.description')
            ->addSelect('storages.original_name')
            ->addSelect('storages.name')
            ->addSelect('storages.type')
            ->addSelect('storages.dir')
            ->leftJoin('materials', 'storages', 'storages', 'storages.id = materials.file')
            ->from('materials')
            ->execute()
            ->fetchAll();

        foreach ($sourcePosts as $sourcePost) {
            $date = $this->source->convertToPHPValue($sourcePost['date'], 'datetime');

            $image = new Image();
            $image->name = $sourcePost['original_name'];
            $image->image = sprintf('%s/%s.%s', $sourcePost['dir'], $sourcePost['name'], $sourcePost['type']);

            $this->destination->insert('blog_posts', [
                'id' => $sourcePost['id'],
                'title' => $sourcePost['title'],
                'slug_value' => $sourcePost['sef'],
                'category_id' => $sourcePost['type_id'],
                'created_at' => $this->destination->convertToDatabaseValue($date, 'datetimetz'),
                'published_at' => $this->destination->convertToDatabaseValue($date, 'datetimetz'),
                'updated_at' => $this->destination->convertToDatabaseValue($date, 'datetimetz'),
                'is_published' => $this->destination->convertToDatabaseValue($sourcePost['is_published'] == '1' ? true : false, 'boolean'),
                'annotation' => $sourcePost['annotation'],
                'content' => $sourcePost['description'],
                'image' => $this->destination->convertToDatabaseValue($image, Image::class)
            ]);
        }
    }
}
