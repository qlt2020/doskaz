<?php


namespace App\Objects\Adding\Steps;

use App\Infrastructure\FileReferenceCollection;
use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

class FirstStep
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $description;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $otherNames;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $address;

    /**
     * @var string|int|null
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     * @EntityExist(entity=App\Objects\Category::class, property="id", message="Категория с ""%property%"": ""%value%"" не существует")
     */
    public $categoryId;

    /**
     * @var array|null
     * @Assert\NotBlank()
     */
    public $point = [];

    /**
     * @var string[]
     */
    public $videos = [];

    /**
     * @var FileReferenceCollection
     * @Assert\Count(min=1, minMessage="Необходимо загрузить не менее 1 фото")
     */
    public $photos = [];
}
