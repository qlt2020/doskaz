<?php


namespace App\Objects;

use Symfony\Component\Validator\Constraints as Assert;

final class Location
{
    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(value=-90.0)
     * @Assert\LessThanOrEqual(value=90.0)
     */
    public $lat;

    /**
     * @var float
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(value=-180.0)
     * @Assert\LessThanOrEqual(value=180.0)
     */
    public $lon;

    /**
     * Location constructor.
     * @param float $lat
     * @param float $lon
     */
    public function __construct(float $lat, float $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }
}
