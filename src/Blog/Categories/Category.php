<?php
declare(strict_types=1);

namespace App\Blog\Categories;

use App\Blog\Meta;
use App\Blog\Slug;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="blog_categories")
 * @Gedmo\TranslationEntity(class="App\Blog\Categories\CategoryTranslation")
 */
class Category
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Gedmo\Translatable()
     */
    private $title;

    /**
     * @var Slug
     * @ORM\Embedded(class="\App\Blog\Slug")
     */
    private $slug;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @var Meta|null
     * @ORM\Column(type=Meta::class, nullable=true, options={"jsonb" = true})
     */
    private $meta;

    /**
     * @Gedmo\Locale()
     */
    private $locale;

    public function __construct(string $title, Slug $slug, ?Meta $meta = null)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->meta = $meta;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id()
    {
        return $this->id;
    }

    public function update(string $title, Slug $slug, ?Meta $meta = null)
    {
        $this->title = $title;
        $this->slug = $slug;
        $this->meta = $meta;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsDeleted()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Slug
     */
    public function getSlug(): Slug
    {
        return $this->slug;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
