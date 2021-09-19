<?php


namespace App\RegionalRepresentatives;

use App\Blog\Image;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="regional_representatives")
 */
class RegionalRepresentative
{
    /**
     * @ORM\Column(type="uuid")
     * @ORM\Id()
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $cityId;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $phone;

    /**
     * @var Image
     * @ORM\Column(type=Image::class, nullable=true, options={"jsonb" = true})
     */
    private $image;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $updatedAt;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * RegionalRepresentative constructor.
     * @param int $cityId
     * @param string $name
     * @param string $email
     * @param string $phone
     * @param Image $image
     */
    public function __construct(int $cityId, string $name, string $email, string $phone, Image $image)
    {
        $this->cityId = $cityId;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->image = $image;

        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->id = Uuid::uuid4();
    }

    public function update(RegionalRepresentativeData $data)
    {
        $this->cityId = $data->cityId;
        $this->email = $data->email;
        $this->name = $data->name;
        $this->image = $data->photo;
        $this->phone = $data->phone;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id()
    {
        return $this->id;
    }

    public function markAsDeleted()
    {
        $this->deletedAt = new \DateTimeImmutable();
    }
}
