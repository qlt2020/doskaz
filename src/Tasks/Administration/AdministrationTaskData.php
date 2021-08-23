<?php


namespace App\Tasks\Administration;

use App\Infrastructure\ObjectResolver\DataObject;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AdministrationTaskData implements DataObject
{
    /**
     * @var UuidInterface|null
     */
    public ?UuidInterface $id;

    /**
     * @Assert\NotBlank()
     */
    public ?string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value=0)
     */
    public ?int $pointsReward;

    public ?int $cityId;

    public ?int $categoryId;

    public ?int $subCategoryId;

    public ?array $area;

    public ?\DateTimeImmutable $closedAt;
}
