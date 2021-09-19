<?php


namespace App\Blog;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearBlogsCommand extends Command
{
    protected static $defaultName = 'app:blogs:truncate';

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->setDescription('Deletes all existing rows from blog categories, posts and comments');
        $this->connection = $connection;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $output->writeln('Starting deleting...');

            $this->connection->transactional(function (Connection $connection) use ($output) {
                $output->writeln('Truncating blog comments...');
                $connection->executeQuery('delete from blog_comments');

                $output->writeln('Truncating blog posts...');
                $connection->executeQuery('delete from blog_posts');

                $output->writeln('Truncating blog categories...');
                $connection->executeQuery('delete from blog_categories');
            });

            $output->writeln('Blog related tables have been successfully truncated!');
        } catch (\Exception $exception) {
            $output->writeln('Error occurred while truncating!');
            $output->writeln($exception->getMessage());
        }
    }
}
