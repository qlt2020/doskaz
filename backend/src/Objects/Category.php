<?php


namespace App\Objects;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity()
 * @ORM\Table(name="object_categories")
 * @Gedmo\TranslationEntity(class="App\Objects\CategoryTranslation")
 */
class Category implements Translatable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Gedmo\Translatable()
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $parentId;

    /**
     * @Gedmo\Locale()
     */
    private $locale;

    /**
     * Category constructor.
     * @param $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
}
