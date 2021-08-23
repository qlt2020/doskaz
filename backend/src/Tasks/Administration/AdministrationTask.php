<?php


namespace App\Tasks\Administration;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="administration_tasks")
 */
class AdministrationTask
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private UuidInterface $id;

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
    private ?\DateTimeImmutable $closedAt;

    /**
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $points;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cityId;

    /**
     * @ORM\Column(type="geometry", options={"geometry_type" = "POLYGON", "srid" = 4326}, nullable=true)
     */
    private $area;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $categoryId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $subCategoryId;

    public function __construct(string $name, int $points, ?int $cityId, ?int $categoryId, ?int $subCategoryId, ?array $area)
    {
        $this->name = $name;
        $this->points = $points;
        $this->cityId = $cityId;
        $this->categoryId = $categoryId;
        $this->subCategoryId = $subCategoryId;
        $this->id = Uuid::uuid4();

        if ($area && count($area)) {
            $this->area = 'SRID=4326;POLYGON((' . implode(', ', array_map(function ($x) {
                return implode(' ', [$x[1], $x[0]]);
            }, $area)) . '))';

            // $this->area
        }

        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function update(string $name, int $points, ?int $cityId, ?int $categoryId, ?int $subCategoryId, ?array $area)
    {
        $this->name = $name;
        $this->points = $points;
        $this->cityId = $cityId;
        $this->categoryId = $categoryId;
        $this->subCategoryId = $subCategoryId;
        if ($area && count($area)) {
            $this->area = 'SRID=4326;POLYGON((' . implode(', ', array_map(function ($x) {
                return implode(' ', [$x[1], $x[0]]);
            }, $area)) . '))';
        } else {
            $this->area = null;
        }
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function close()
    {
        $this->closedAt = new \DateTimeImmutable();
    }

    public function completeForUser(int $userId, UuidInterface $objectId): UserAdministrationTask
    {
        return new UserAdministrationTask($userId, $this->id, $this->points, $objectId);
    }
}
