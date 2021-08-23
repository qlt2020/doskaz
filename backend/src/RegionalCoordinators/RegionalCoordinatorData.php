<?php


namespace App\RegionalCoordinators;

use App\Infrastructure\ObjectResolver\DataObject;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegionalCoordinatorData implements DataObject
{
    public ?UuidInterface $id;

    /**
     * @Assert\NotBlank()
     */
    public ?int $userId;

    /**
     * @Assert\Count(min=1)
     * @Assert\NotBlank()
     */
    public ?CityIdCollection $cities;

    /**
     * RegionalCoordinatorData constructor.
     * @param int $id
     * @param CityIdCollection $cities
     */
    public function __construct(?UuidInterface $id = null, ?int $userId = null, ?CityIdCollection $cities = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->cities = $cities;
    }
}
