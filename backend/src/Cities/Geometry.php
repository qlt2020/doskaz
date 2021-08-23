<?php


namespace App\Cities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cities_geometry")
 */
class Geometry
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="geometry", options={"geometry_type" = "POLYGON", "srid" = 4326})
     */
    private $geometry;
}
