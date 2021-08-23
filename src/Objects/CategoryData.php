<?php


namespace App\Objects;

use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Категория объекта", schema="ObjectCategory")
 */
class CategoryData
{
    /**
     * @var integer
     * @Property()
     */
    public $id;

    /**
     * @var string
     * @Property()
     */
    public $title;

    /**
     * @var string
     * @Property(nullable=true, example="fa-building")
     */
    public $icon;

    /**
     * @var array
     * @Property(type="array", description="Подкатегории", example={}, items={@Schema(ref="#/components/schemas/ObjectCategory")})
     */
    public $subCategories;

    /**
     * CategoryData constructor.
     * @param int $id
     * @param string $title
     * @param string $icon
     * @param array $subCategories
     */
    public function __construct(int $id, string $title, ?string $icon, array $subCategories)
    {
        $this->id = $id;
        $this->title = $title;
        $this->icon = $icon;
        $this->subCategories = $subCategories;
    }
}
