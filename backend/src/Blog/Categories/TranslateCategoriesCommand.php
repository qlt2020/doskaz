<?php


namespace App\Blog\Categories;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TranslateCategoriesCommand extends Command
{
    protected static $map = [
        'Новости' => 'Жаңалықтар',
        'Обучение' => 'Оқыту',
        'Видео' => 'Видео',
        'Мероприятия' => 'Іс-шаралар',
        'Статьи' => 'Мақалалар',
        'Законодательство' => 'Заңнама',
        'Опросы' => 'Сауалнамалар'
    ];

    protected static $defaultName = 'app:categories:translate';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->entityManager->getRepository(Category::class)->findAll() as $category) {
            $category->setTranslatableLocale('kz');
            $category->setTitle(self::$map[$category->getTitle()]);
        }
        $this->entityManager->flush();
    }
}
