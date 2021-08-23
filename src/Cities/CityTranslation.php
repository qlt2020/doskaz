<?php


namespace App\Cities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Entity()
 * @ORM\Table(name="city_translations")
 */
class CityTranslation extends AbstractTranslation
{
}
