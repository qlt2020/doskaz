<?php
declare(strict_types=1);

namespace App\Complaints;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use OpenApi\Annotations\Discriminator;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM()
 * @DiscriminatorMap(typeProperty="type", mapping={
 *     "complaint1"="App\Complaints\ComplaintType1",
 *     "complaint2"="App\Complaints\ComplaintType2",
 * })
 * @Schema(
 *     title="Содержимое жалобы",
 *     schema="AbstractComplaintContent",
 *     oneOf={
 *         @Schema(ref="#/components/schemas/ComplaintContent1"),
 *         @Schema(ref="#/components/schemas/ComplaintContent2")
 *     },
 *     discriminator={
 *         @Discriminator(
 *             propertyName="type",
 *             mapping={
 *                 "complaint1"="#/components/schemas/ComplaintContent1",
 *                 "complaint2"="#/components/schemas/ComplaintContent2"
 *             }
 *         )
 *     }
 * )
 */
class ComplaintContent
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Property(example="complaint1")
     */
    public $type;

    /**
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     * @Property()
     */
    public $visitedAt;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Наименование объекта")
     */
    public $objectName;

    /**
     * @var string|int
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Id города")
     */
    public $cityId;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Улица")
     */
    public $street;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Номер дома")
     */
    public $building;

    /**
     * @var string|null
     * @Property(description="Номер офиса")
     */
    public $office;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Property(nullable=false, description="Цель посещения")
     */
    public $visitPurpose;

    /**
     * @var string[]
     * @Property()
     */
    public $videos = [];

    /**
     * @var string[]
     * @Assert\Count(min=1)
     * @Assert\All(constraints={@Assert\NotBlank()})
     * @Property()
     */
    public $photos = [];

    /**
     * ComplaintContent constructor.
     * @param string $type
     * @param \DateTimeImmutable $visitedAt
     * @param string $objectName
     * @param int|string $cityId
     * @param string $street
     * @param string $building
     * @param string|null $office
     * @param string $visitPurpose
     * @param string[] $videos
     * @param string[] $photos
     */
    public function __construct(string $type, ?\DateTimeImmutable $visitedAt, ?string $objectName, $cityId, ?string $street, ?string $building, ?string $office, ?string $visitPurpose, ?array $videos, ?array $photos)
    {
        $this->type = $type;
        $this->visitedAt = $visitedAt;
        $this->objectName = $objectName;
        $this->cityId = $cityId;
        $this->street = $street;
        $this->building = $building;
        $this->office = $office;
        $this->visitPurpose = $visitPurpose;
        $this->videos = $videos;
        $this->photos = $photos;
    }
}
