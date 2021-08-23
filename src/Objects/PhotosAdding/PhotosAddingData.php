<?php


namespace App\Objects\PhotosAdding;

use App\Infrastructure\FileReferenceCollection;
use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class PhotosAddingData implements DataObject
{
    /**
     * @var FileReferenceCollection
     * @Assert\Count(min=1, minMessage="Необходимо загрузить не менее 1 фото")
     */
    public $photos = [];
}
