<?php


namespace App\Objects;

use App\Infrastructure\FileReferenceCollection;
use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class MapObjectData implements DataObject
{
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @Assert\NotBlank()
     */
    public $address;

    public $description;

    /**
     * @Assert\NotBlank()
     */
    public $categoryId;

    /**
     * @Assert\NotBlank()
     */
    public $point;

    /**
     * @var string[]
     */
    public $videos;

    /**
     * @var FileReferenceCollection
     * @Assert\All(
     *     constraints={
     *         @Assert\Image()
     *     }
     * )
     */
    public $photos;

    /**
     * @var Zones
     * @Assert\Valid()
     */
    public $zones;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $otherNames;

    /**
     * MapObjectData constructor.
     * @param $title
     * @param $address
     * @param $description
     * @param $categoryId
     * @param $point
     * @param array $videos
     * @param FileReferenceCollection $photos
     * @param Zones $zones
     * @param $id
     * @param null $otherNames
     */
    public function __construct($title, $address, $description, $categoryId, $point, array $videos, FileReferenceCollection $photos, $zones, $id = null, $otherNames = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->address = $address;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->point = $point;
        $this->videos = $videos;
        $this->photos = $photos;
        $this->zones = $zones;
        $this->otherNames = $otherNames;
    }
}
