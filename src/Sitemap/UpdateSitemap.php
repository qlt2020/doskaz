<?php


namespace App\Sitemap;

use Doctrine\DBAL\Connection;
use samdark\sitemap\Sitemap;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateSitemap extends Command
{
    protected static $defaultName = 'sitemap:update';

    private Sitemap $sitemap;

    private Connection $connection;

    private string $host = 'https://doskaz.kz';

    public function __construct($path, Connection $connection)
    {
        parent::__construct();
        $this->sitemap = new Sitemap($path);
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sitemap->addItem($this->host . '/contacts');
        $this->sitemap->addItem($this->host . '/about');
        $this->sitemap->addItem($this->host . '/blog');
        $this->updateBlog();
        $this->updateObjects();
        $this->sitemap->write();
    }

    private function updateBlog()
    {
        $statement = $this->connection->createQueryBuilder()
            ->addSelect('blog_posts.slug_value slug')
            ->addSelect('blog_categories.slug_value as category_slug')
            ->from('blog_posts')
            ->join('blog_posts', 'blog_categories', 'blog_categories', 'blog_categories.id = blog_posts.category_id')
            ->andWhere('blog_posts.is_published = true and blog_posts.deleted_at is null')
            ->andWhere('blog_posts.published_at <= CURRENT_TIMESTAMP')
            ->execute();

        while ($row = $statement->fetch()) {
            $this->sitemap->addItem($this->host . '/blog/' . $row['category_slug'] . '/' . $row['slug']);
        }

        $blogCategories = $this->connection->createQueryBuilder()->select('slug_value')->from('blog_categories')->where('deleted_at is null')->execute()->fetchAll();

        foreach ($blogCategories as $blogCategory) {
            $this->sitemap->addItem($this->host . '/blog/' . $blogCategory['slug_value']);
        }
    }

    private function updateObjects()
    {
        $statement = $this->connection->createQueryBuilder()
            ->addSelect('id')
            ->from('objects')
            ->andWhere('deleted_at is null')
            ->execute();

        while ($row = $statement->fetch()) {
            $this->sitemap->addItem($this->host . '/objects/' . $row['id']);
        }
    }
}
