<?php


namespace App\Cities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cities")
 * @Gedmo\TranslationEntity(class="App\Cities\CityTranslation")
 */
class Cities
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Gedmo\Translatable()
     */
    private $name;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type" = "POLYGON"})
     */
    private $bbox;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @Gedmo\Locale()
     */
    private $locale;

    public function __construct(int $id, string $name, int $priority)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }

    public static function list()
    {
        return [
            ['id' => 106724, 'name' => 'Нур-Султан', 'nameKz' => 'Нұр-Сұлтан', 'bounds' => [[51.0006766, 71.2244414], [51.3511101, 71.7851913]]],
            ['id' => 158106, 'name' => 'Алматы', 'nameKz' => 'Алматы', 'bounds' => [[43.0328438, 76.7382775], [43.4036849, 77.1667539]]],
            ['id' => 110170, 'name' => 'Актау', 'nameKz' => 'Ақтау'],
            ['id' => 68402, 'name' => 'Актобе', 'nameKz' => 'Ақтөбе'],
            ['id' => 26551, 'name' => 'Атырау', 'nameKz' => 'Атырау'],
            ['id' => 80696, 'name' => 'Капшагай', 'nameKz' => 'Қапшағай'],
            ['id' => 212922, 'name' => 'Караганда', 'nameKz' => 'Қарағанды'],
            ['id' => 155241, 'name' => 'Кокшетау', 'nameKz' => 'Көкшетау'],
            ['id' => 125193, 'name' => 'Костанай', 'nameKz' => 'Қостанай'],
            ['id' => 165288, 'name' => 'Кызылорда', 'nameKz' => 'Қызылорда'],
            ['id' => 9103, 'name' => 'Павлодар', 'nameKz' => 'Павлодар', 'bounds' => [[52.2234455, 76.8608794], [52.3988251, 77.12136]]],
            ['id' => 33335, 'name' => 'Семей', 'nameKz' => 'Семей'],
            ['id' => 79497, 'name' => 'Талдыкорган', 'nameKz' => 'Талдықорған'],
            ['id' => 168533, 'name' => 'Тараз', 'nameKz' => 'Тараз'],
            ['id' => 182036, 'name' => 'Туркестан', 'nameKz' => 'Түркістан'],
            ['id' => 178771, 'name' => 'Шымкент', 'nameKz' => 'Шымкент'],
            ['id' => 51071, 'name' => 'Уральск', 'nameKz' => 'Орал'],
            ['id' => 31223, 'name' => 'Усть-Каменогорск', 'nameKz' => 'Өскемен'],
            ['id' => 113407, 'name' => 'Петропавловск', 'nameKz' => 'Петропавл'],
            ['id' => 1313, 'name' => 'Экибастуз', 'nameKz' => 'Екібастұз'],
            ['id' => 9, 'name' => 'Аксу', 'nameKz' => 'Ақсу'],
            ['id' => 211201, 'name' => 'Балхаш', 'nameKz' => 'Балқаш'],
            ['id' => 211888, 'name' => 'Жезказган', 'nameKz' => 'Жезқазған'],
            ['id' => 156843, 'name' => 'Степногорск', 'nameKz' => 'Степногорск'],
            ['id' => 125938, 'name' => 'Аркалык', 'nameKz' => 'Арқалық'],
            ['id' => 214204, 'name' => 'Сарань', 'nameKz' => 'Саран'],
            ['id' => 180937, 'name' => 'Арыс', 'nameKz' => 'Арыс'],
            ['id' => 181562, 'name' => 'Кентау', 'nameKz' => 'Кентау'],
            ['id' => 212498, 'name' => 'Каражал', 'nameKz' => 'Қаражал'],
            ['id' => 81062, 'name' => 'Текели', 'nameKz' => 'Текелі'],
            ['id' => 126778, 'name' => 'Рудный', 'nameKz' => 'Рудный'],
            ['id' => 168004, 'name' => 'Байконыр', 'nameKz' => 'Байқоңыр'],
            ['id' => 215326, 'name' => 'Шахтинск', 'nameKz' => 'Шахтинск'],
            ['id' => 214126, 'name' => 'Приозерск', 'nameKz' => 'Приозерск'],
            ['id' => 126589, 'name' => 'Лисаковск', 'nameKz' => 'Лисаковск'],
            ['id' => 214935, 'name' => 'Темиртау', 'nameKz' => 'Теміртау'],
            ['id' => 32845, 'name' => 'Риддер', 'nameKz' => 'Риддер'],
            ['id' => 110672, 'name' => 'Жанаозен', 'nameKz' => 'Жаңаөзен'],
            ['id' => 214573, 'name' => 'Сатпаев', 'nameKz' => 'Сәтбаев'],
            ['id' => 32808, 'name' => 'Курчатов', 'nameKz' => 'Курчатов'],
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public static function find(int $id): ?array
    {
        foreach (self::list() as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBbox()
    {
        return $this->bbox;
    }
}
