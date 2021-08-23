<?php


namespace App\RegionalCoordinators;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="regional_coordinators")
 */
class RegionalCoordinator
{
    /**
     * @var UuidInterface
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    private int $userId;

    /**
     * @ORM\Column(type=App\RegionalCoordinators\CityIdCollection::class, options={"jsonb" = true})
     */
    private CityIdCollection $cities;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private \DateTimeImmutable $updatedAt;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $deletedAt;

    public function __construct(UuidInterface $id, int $userId, CityIdCollection $cities)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->cities = $cities;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAsDeleted()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }

    public function updateCities(CityIdCollection $cityIdCollection)
    {
        $this->cities = $cityIdCollection;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function userId(): int
    {
        return $this->userId;
    }
}
