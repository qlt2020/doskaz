<?php


namespace App\Tasks;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $cityId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $categoryId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $subcategoryId;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type" = "POLYGON", "srid" = 4326}, nullable=true)
     */
    private $region;
}
