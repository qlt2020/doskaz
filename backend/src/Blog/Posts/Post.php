<?php


namespace App\Blog\Posts;

use App\Blog\Image;
use App\Blog\Meta;
use App\Blog\MetaData;
use App\Blog\Slug;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blog_posts")
 * @ORM\Entity()
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $annotation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $annotation_kz;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $annotation_en;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_kz;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content_en;

    /**
     * @var Slug
     * @ORM\Embedded(class="\App\Blog\Slug")
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private $categoryId;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isPublished = true;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @var Meta
     * @ORM\Embedded(class="\App\Blog\Meta")
     */
    private $meta;

    /**
     * @var Image
     * @ORM\Column(type=Image::class, nullable=true, options={"jsonb" = true})
     */
    private $image;

    /**
     * @var Image
     * @ORM\Column(type=Image::class, nullable=true, options={"jsonb" = true})
     */
    private $image_kz;

    /**
     * @var Image
     * @ORM\Column(type=Image::class, nullable=true, options={"jsonb" = true})
     */
    private $image_en;

    public function __construct(PostData $postData, ?Image $image, Meta $meta, Slug $slug)
    {
        $this->title = $postData->title;
        $this->title_kz = $postData->title_kz;
        $this->title_en = $postData->title_en;
        $this->categoryId = $postData->categoryId;
        $this->publishedAt = $postData->publishedAt;
        $this->isPublished = $postData->isPublished;
        $this->image = $image;
        $this->image_kz = $postData->image_kz;
        $this->image_en = $postData->image_en;
        $this->meta = $meta;
        $this->slug = $slug;
        $this->annotation = $postData->annotation;
        $this->annotation_kz = $postData->annotation_kz;
        $this->annotation_en = $postData->annotation_en;
        $this->content = $postData->content;
        $this->content_kz = $postData->content_kz;
        $this->content_en = $postData->content_en;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function markAsDeleted()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function update(PostData $postData, ?Image $image, Meta $meta, Slug $slug)
    {
        $this->title = $postData->title;
        $this->title_kz = $postData->title_kz;
        $this->title_en = $postData->title_en;
        $this->categoryId = $postData->categoryId;
        $this->publishedAt = $postData->publishedAt;
        $this->isPublished = $postData->isPublished;
        $this->image = $image;
        $this->image_kz = $postData->image_kz;
        $this->image_en = $postData->image_en;
        $this->meta = $meta;
        $this->slug = $slug;
        $this->annotation = $postData->annotation;
        $this->annotation_kz = $postData->annotation_kz;
        $this->annotation_en = $postData->annotation_en;
        $this->content = $postData->content;
        $this->content_kz = $postData->content_kz;
        $this->content_en = $postData->content_en;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getTranslation($locale, $field): ?string
    {
        return $this->{$field . ($locale === 'ru' ? '' : '_' .$locale)};
    }
}
