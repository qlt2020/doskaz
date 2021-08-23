<?php
declare(strict_types=1);

namespace App\Blog;

use Doctrine\ORM\Mapping as ORM;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ORM\Embeddable()
 * @ODM()
 */
final class Meta
{
    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    public $title;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    public $description;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    public $keywords;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    public $ogTitle;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    public $ogDescription;

    /**
     * @var Image
     * @ORM\Column(type=Image::class, nullable=true, options={"jsonb": true})
     */
    public $ogImage;

    public function __construct(?string $title = null, ?string $description = null, ?string $keywords = null, ?string $ogTitle = null, ?string $ogDescription = null, ?Image $ogImage = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->ogTitle = $ogTitle;
        $this->ogDescription = $ogDescription;
        $this->ogImage = $ogImage;
    }

    public static function fromMetaData(?MetaData $data): self
    {
        if ($data) {
            return new self(
                $data->title,
                $data->description,
                $data->keywords,
                $data->ogTitle,
                $data->ogDescription,
                $data->ogImage
            );
        }
        return new self();
    }
}
