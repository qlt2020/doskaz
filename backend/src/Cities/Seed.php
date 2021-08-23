<?php


namespace App\Cities;

use Doctrine\DBAL\Connection;
use OpenCage\Geocoder\Geocoder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Seed extends Command
{
    protected static $defaultName = 'app:cities:seed';

    private $geocoder;

    private const CITIES = [
        ['id' => 106724, 'name' => 'Нур-Султан'],
        ['id' => 158106, 'name' => 'Алматы'],
        ['id' => 110170, 'name' => 'Актау'],
        ['id' => 68402, 'name' => 'Актобе'],
        ['id' => 26551, 'name' => 'Атырау'],
        ['id' => 80696, 'name' => 'Капшагай'],
        ['id' => 212922, 'name' => 'Караганда'],
        ['id' => 155241, 'name' => 'Кокшетау'],
        ['id' => 125193, 'name' => 'Костанай'],
        ['id' => 165288, 'name' => 'Кызылорда'],
        ['id' => 9103, 'name' => 'Павлодар'],
        ['id' => 33335, 'name' => 'Семей'],
        ['id' => 79497, 'name' => 'Талдыкорган'],
        ['id' => 168533, 'name' => 'Тараз'],
        ['id' => 182036, 'name' => 'Туркестан'],
        ['id' => 178771, 'name' => 'Шымкент'],
        ['id' => 51071, 'name' => 'Уральск'],
        ['id' => 31223, 'name' => 'Усть-Каменогорск'],
        ['id' => 113407, 'name' => 'Петропавловск'],
        ['id' => 1313, 'name' => 'Экибастуз'],
        ['id' => 9, 'name' => 'Аксу', 'bounds' => [
            'southwest' => [
                'lng' => 76.896143,
                'lat' => 52.016733,
            ],
            'northeast' => [
                'lng' => 76.958284,
                'lat' => 52.057768
            ]
        ]],
        ['id' => 211201, 'name' => 'Балхаш'],
        ['id' => 211888, 'name' => 'Жезказган'],
        ['id' => 156843, 'name' => 'Степногорск'],
        ['id' => 125938, 'name' => 'Аркалык'],
        ['id' => 214204, 'name' => 'Сарань'],
        ['id' => 180937, 'name' => 'Арыс'],
        ['id' => 181562, 'name' => 'Кентау'],
        ['id' => 212498, 'name' => 'Каражал'],
        ['id' => 81062, 'name' => 'Текели'],
        ['id' => 126778, 'name' => 'Рудный'],
        ['id' => 168004, 'name' => 'Байконыр'],
        ['id' => 215326, 'name' => 'Шахтинск'],
        ['id' => 214126, 'name' => 'Приозерск', 'bounds' => [
            'southwest' => [
                'lng' => 73.660240,
                'lat' => 46.021399,
            ],
            'northeast' => [
                'lng' => 73.718219,
                'lat' => 46.044644
            ]
        ]],
        ['id' => 126589, 'name' => 'Лисаковск'],
        ['id' => 214935, 'name' => 'Темиртау'],
        ['id' => 32845, 'name' => 'Риддер'],
        ['id' => 110672, 'name' => 'Жанаозен'],
        ['id' => 214573, 'name' => 'Сатпаев'],
        ['id' => 32808, 'name' => 'Курчатов'],
    ];
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection, Geocoder$geocoder)
    {
        // $this->geocoder = new Geocoder('6e305b2b72264cfebe6da59f77a2aacc');
        $this->connection = $connection;
        parent::__construct();
        $this->geocoder = $geocoder;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $boundsIndexed = array_map(function ($city) {
            if (array_key_exists('bounds', $city)) {
                return $city['bounds'];
            }

            try {
                $result = $this->geocoder->geocode($city['name'], ['countrycode' => 'kz'])['results'];

                foreach ($result as $item) {
                    if ($item['components']['_type'] === 'city') {
                        return $item['bounds'];
                    }
                }
            } catch (\Exception $exception) {
                dd($city);
            }
        }, self::CITIES);


        $this->connection->transactional(function (Connection $connection) use ($boundsIndexed) {
            $connection->exec('TRUNCATE cities');

            foreach (self::CITIES as $index => $city) {
                $bounds = $boundsIndexed[$index];
                $query = '
                    INSERT INTO cities (id, name, priority, bbox) 
                    VALUES (:id, :name, :priority, ST_MakeEnvelope(:xmin, :ymin, :xmax, :ymax, 4326))
                ';

                $connection->executeUpdate($query, [
                    'id' => $city['id'],
                    'name' => $city['name'],
                    'priority' => $index * 100,
                    'xmin' => $bounds['southwest']['lng'],
                    'ymin' => $bounds['southwest']['lat'],
                    'xmax' => $bounds['northeast']['lng'],
                    'ymax' => $bounds['northeast']['lat']
                ]);
            }
        });
    }
}
