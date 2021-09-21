<?php
declare(strict_types=1);

namespace App\Complaints;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * @ODM()
 * @Schema(title="Данные заявителя", schema="Complainant")
 */
final class Complainant
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Имя")
     */
    public $firstName;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Фамилия")
     */
    public $lastName;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Отчество")
     */
    public $middleName;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Length(12)
     * @Assert\Regex(pattern="/^\d+$/")
     * @Property(nullable=false, description="ИИН")
     */
    public $iin;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Номер телефона")
     * @Assert\Regex(pattern="/\+7\(\d{3}\)\d{3}-\d{2}-\d{2}/")
     */
    public $phone;

    /**
     * @var string|int|null
     * @Assert\NotBlank()
     * @AcmeAssert\ContainsExistsInDB(table="cities")
     * @Property(nullable=false, description="id города")
     */
    public $cityId;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Улица")
     */
    public $street;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Номер дома")
     */
    public $building;

    /**
     * @var string|null
     * @Property(type="string", nullable=true, description="Квартира")
     */
    public $apartment;
}
