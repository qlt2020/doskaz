<?php


namespace App\Objects;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Entity()
 * @ORM\Table(name="category_translations")
 */
class CategoryTranslation extends AbstractTranslation
{
}
