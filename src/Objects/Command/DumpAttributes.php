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

class DumpAttributes extends Command
{
    protected static $defaultName = 'app:objects:dump-attributes';
    /**
     * @var Connection
     */
    private Connection $connection;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        //  dd($t);
        parent::__construct();
        $this->connection = $connection;
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $q = $this->entityManager->createQueryBuilder()->select('c')->from(Category::class, 'c')
            ->getQuery();

        $q->setHint(
            \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            'kk' // take locale from session or request etc.
        );

        /* $q->setHint(
             \Gedmo\Translatable\TranslatableListener::HINT_FALLBACK,
             1 // fallback to default values in case if record is not translated
         );*/


        $this->entityManager->persist(new Category("test"));
        $this->entityManager->flush();


        //    dd($this->entityManager->find(Category::class, 137));


        // $c[0]->setLocale('ru');
        dd($q->execute());
        /* $cities = $this->connection->createQueryBuilder()
             ->select('name')
             ->from('cities')
             ->execute()
             ->fetchAll(FetchMode::COLUMN);

         $categories = $this->connection->createQueryBuilder()
             ->select('title')
             ->from('object_categories')
             ->execute()
             ->fetchAll(FetchMode::COLUMN);


         file_put_contents('x.txt', implode("\n", array_merge(AttributesConfiguration::getFlatList(), $cities, $categories)));*/
    }
}
