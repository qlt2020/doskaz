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
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $title_kz;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $title_en;

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
     * @ORM\Column(type="json", nullable=true, options={"jsonb" = true})
     */
    private $meta;

    /**
     * @Gedmo\Locale()
     */
    private $locale;

    public function __construct(CategoryData $categoryData, Slug $slug, ?Meta $meta = null)
    {
        $this->title = $categoryData->title;
        $this->title_kz = $categoryData->title_kz;
        $this->title_en = $categoryData->title_en;
        $this->slug = $slug;
        $this->meta = $meta;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id()
    {
        return $this->id;
    }

    public function update(CategoryData $categoryData, Slug $slug, ?Meta $meta = null)
    {
        $this->title = $categoryData->title;
        $this->title_kz = $categoryData->title_kz;
        $this->title_en = $categoryData->title_en;
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

    public function getTranslation($locale, $field): ?string
    {
        return $this->{$field . ($locale === 'ru' ? '' : '_' . $locale)};
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

    /**
     * @return string|null
     */
    public function getTitleKz(): ?string
    {
        return $this->title_kz;
    }

    /**
     * @param string|null $title_kz
     */
    public function setTitleKz(?string $title_kz): void
    {
        $this->title_kz = $title_kz;
    }

    /**
     * @return string|null
     */
    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    /**
     * @param string|null $title_en
     */
    public function setTitleEn(?string $title_en): void
    {
        $this->title_en = $title_en;
    }

}
