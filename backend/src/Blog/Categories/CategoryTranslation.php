<?php


namespace App\Blog\Categories;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Entity()
 * @ORM\Table(name="blog_category_translations")
 */
class CategoryTranslation extends AbstractTranslation
{
}
