<?php


namespace App\Objects\Adding;

use App\Objects\MapObject;
use App\Objects\Point;
use Doctrine\ORM\Mapping as ORM;
use App\Objects\Adding\Form;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="adding_requests")
 */
class AddingRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var integer|null
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $userId;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @var Form
     * @ORM\Column(type=Form::class, options={"jsonb" = true})
     */
    private $data;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $approvedAt;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    public function __construct(int $userId, Form $data)
    {
        $this->userId = $userId;
        $this->data = $data;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function updateData(Form $data)
    {
        Assert::null($this->deletedAt);
        $this->data = $data;
    }

    public function approve(): MapObject
    {
        Assert::null($this->deletedAt);
        Assert::null($this->approvedAt);
        $this->approvedAt = new \DateTimeImmutable();

        /**
         * @var $data MiddleFormRequestData|FullFormRequestData
         */
        $data = $this->data;

        return MapObject::createFromRequest(
            $this->id,
            Point::fromLatLong($data->first->point[0], $data->first->point[1]),
            $data->first->name,
            $data->first->categoryId,
            $data->toZones(),
            $data->first->address,
            $data->first->description,
            $data->first->photos,
            $data->first->videos,
            $this->userId,
            $data->first->otherNames
        );
    }

    public function markAsDeleted()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }
}
