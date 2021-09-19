<?php

namespace App\Blog;

use App\Blog\Categories\Category;
use App\Blog\Categories\CategoryData;
use App\Blog\Categories\CategoryRepository;
use App\Blog\Meta;
use App\Blog\SlugFactory;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\Firebase\Exception;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateHelpBlogCategoryCommand extends Command
{
    protected static $defaultName = 'app:create-help-blog-category';
    private $categoryData;
    private $categoryRepository;
    private $slugFactory;
    private $flusher;


    public function __construct(CategoryRepository $categoryRepository, SlugFactory $slugFactory, Flusher $flusher)
    {
        $this->categoryData = new CategoryData();
        $this->categoryData->title = 'Помощь';
        $this->categoryData->title_kz = 'Көмек';
        $this->categoryData->title_en = 'Help';

        $this->slugFactory = $slugFactory;
        $this->categoryRepository = $categoryRepository;
        $this->flusher = $flusher;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a BlogCategory with name Help.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a Help Blog Category')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $category = new Category($this->categoryData, $this->slugFactory->make($this->categoryData->slug ?: $this->categoryData->title));
            $this->categoryRepository->add($category);
            $this->flusher->flush();

            $output->writeln('Category created successfully');
        }catch (Exception $exception){
            $output->writeln($exception->getMessage());
        }

    }
}