<?php


namespace App\Objects\Command;

use App\Objects\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCategoryEnTranslations extends Command
{
    protected static $defaultName = 'app:objects:update-category-en-translations';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    private static $map = [
        'Государственные учреждения' => 'State institutions',
        'Здравоохранение' => 'Health care',
        'Культура и досуг' => 'Culture and entertainment',
        'Образование' => 'Education',
        'Торговля и услуги' => 'Goods and services',
        'Питание' => 'Food',
        'Транспорт' => 'Transport',
        'Спорт' => 'Sport',
        'Финансы' => 'Finance',
        'Религия' => 'Religion',
        'Проживание' => 'Lodging',
        'Акимат' => 'Akimat',
        'Государственное учреждение' => 'State institution',
        'Полиция' => 'Police',
        'Суд, прокуратура, юстиция' => 'Court, prosecutor’s office, justice department',
        'ЦОН' => 'CSC',
        'Аптека' => 'Pharmacy',
        'Больница' => 'Hospital',
        'Ветеринарная клиника' => 'Vet clinic',
        'Оптика' => 'Optician’s',
        'Поликлиника' => 'Polyclinic',
        'Санаторий' => 'Sanatorium',
        'Стоматология' => 'Dentist',
        'Частная клиника' => 'Private clinic',
        'Библиотека' => 'Library',
        'Дворец, дом культуры' => 'Palace, house of culture',
        'Дворец, дом школьников' => 'Palace, house of schoolchildren',
        'Дворовый клуб, секции, кружки' => 'Neighbourhood club, special interest club',
        'Детская площадка' => 'Playground',
        'Достопримечательность' => 'Sight',
        'Караоке' => 'Karaoke',
        'Кинотеатр' => 'Cinema',
        'Концертный зал' => 'Concert hall',
        'Музей' => 'Museum',
        'Ночной клуб' => 'Night club',
        'Парк' => 'Park',
        'Театр' => 'Theatre',
        'Другое' => 'Other',
        'Автошкола' => 'Driving school',
        'Детский сад' => 'Kindergarten',
        'Институт, университет' => 'Institute, university',
        'Колледж, ТиПО' => 'College, technical and vocational education institutions',
        'Музыкальная школа' => 'Music school',
        'Учебный центр, курсы' => 'Study center, courses',
        'Художественная школа' => 'Art school',
        'Школа, лицей, гимназия' => 'School, lyceum, gymnasium',
        'Автосалон, автозапчасти' => 'Car dealership, car parts',
        'Адвокат' => 'Advocate',
        'Базар, рынок' => 'Bazaar, market',
        'Баня' => 'Sauna',
        'Бизнес-центр' => 'Business center',
        'Букмекерская контора' => 'Bookmaker office',
        'Интернет-кафе' => 'Internet cafe',
        'Канцелярские товары' => 'Stationery',
        'Книжный магазин' => 'Bookstore',
        'Курьерская доставка' => 'Express delivery',
        'Магазин бытовой техники и электроники' => 'Household appliances and electronics store',
        'Магазин игрушек' => 'Toy shop',
        'Магазин одежды, обуви' => 'Clothing and shoe store',
        'Магазин строительных товаров' => 'Hardware store',
        'Магазин хозяйственных товаров' => 'Housewares store',
        'Мебельный магазин' => 'Furniture store',
        'Магазин другой' => 'Other kind of store',
        'Нотариус' => 'Notary',
        'Общественный туалет' => 'Public toilet',
        'Парикмахерская, салон красоты' => 'Barbershop, beauty salon',
        'Полиграфические услуги' => 'Printing services',
        'Почтовое отделение' => 'Post office',
        'Продуктовый магазин' => 'Grocery store',
        'Прокат велосипедов' => 'Bike rental',
        'Ремонт бытовой техники' => 'Repair of household appliances',
        'Сотовая связь' => 'Mobile service',
        'Магазин спортивных товаров' => 'Sports goods',
        'Страховая компания' => 'Insurance company',
        'Супермаркет' => 'Supermarket',
        'Телекоммуникации' => 'Telecommunication',
        'Торгово-развлекательный центр' => 'Shopping and entertainment center',
        'Торговый дом/ центр' => 'Trading house / center',
        'Туристическое агентство' => 'Travel agency',
        'Фотосалон' => 'Photo studio',
        'Химчистка, ремонт обуви' => 'Dry cleaner’s, shoe repair',
        'Цветочный магазин' => 'Flower shop',
        'Швейная мастерская, ателье' => 'Tailor shop, atelier',
        'Ювелирные изделия' => 'Jewelry',
        'Бар' => 'Bar',
        'Кафе' => 'Cafe',
        'Кофейня' => 'Coffee house',
        'Ресторан' => 'Restaurant',
        'Фастфуд' => 'Fastfood',
        'Банк' => 'Bank',
        'Банкомат' => 'ATM',
        'Ломбард' => 'Pawn shop',
        'Обменный пункт' => 'Exchange office',
        'Мечеть' => 'Mosque',
        'Синагога' => 'Synagogue',
        'Храм' => 'Temple',
        'Церковь' => 'Church',
        'Автовокзал' => 'Coach station',
        'Автосервис, технический осмотр' => 'Car service, tech inspection',
        'АЗС' =>  'Gas station',
        'Аэропорт' => 'Airport',
        'Грузоперевозки' => 'Freight transportation',
        'Железнодорожный вокзал' =>  'Train station',
        'Остановка общественного транспорта' => 'Public transport stop',
        'Парковка' =>  'Parking',
        'Такси' => 'Taxi',
        'Шиномонтаж' =>  'Tyre repair',
        'Аренда жилья' => 'Housing rental',
        'Гостиница, отель' => 'Guesthouse, hotel',
        'Жилой дом' => 'Residential building',
        'Общежитие' =>  'Dormitory',
        'Бассейн' => 'Swimming pool',
        'Дворец спорта' => 'Palace of sports',
        'Ледовый дворец, каток' => 'Ice arena, ice skating rink',
        'Спортзал' => 'Gym',
        'Спортивный клуб, школа' => 'Sports club, sports school',
        'Спортивный комплекс' => 'Sports complex',
        'Спортплощадка' => 'Sports ground',
        'Стадион' => 'Stadium',
        'Фитнес-клуб' => 'Fitness club',
        'Столовая' => 'Canteen',
        'Авиакасса/ железнодорожная касса' => 'Air ticket office / railway ticket office',
        'Культурный центр/ развлекательный комплекс' => 'Cultural center/ Entertainment complex ',
        'Переводческое бюро' =>  'Translation agency',
        'Неправительственная организация' => 'Non-governmental organization'
    ];

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $categories Category[]
         */
        $categories = $this->entityManager->createQueryBuilder()->select('c')->from(Category::class, 'c')
            ->getQuery()->execute();

        foreach ($categories as $category) {
            $category->setTitle(self::$map[$category->getTitle()]);
            $category->setTranslatableLocale('en');
        }
        $this->entityManager->flush();
    }
}
