<?php


namespace App\Cities;

use OpenApi\Annotations\Items;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;

/**
 * @Schema(title="Город", schema="City")
 */
class City
{
    /**
     * @var int
     * @Property(example=106724)
     */
    public $id;

    /**
     * @var string
     * @Property()
     */
    public $name;

    /**
     * @var array
     * @Property(
     *     type="array",
     *     example={{51.0006766, 71.2244414}, {51.3511101, 71.7851913}},
     *     @Items(
     *         type="array",
     *         @Items(
     *            type="array",
     *            @Items(type="number", format="float")
     *         )
     *     )
     * )
     */
    public $bounds;

    /**
     * City constructor.
     * @param int $id
     * @param string $name
     * @param array $bounds
     */
    public function __construct(int $id, string $name, array $bounds)
    {
        $this->id = $id;
        $this->name = $name;
        $this->bounds = $bounds;
    }
}
