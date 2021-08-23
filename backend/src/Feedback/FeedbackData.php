<?php


namespace App\Feedback;

use App\Infrastructure\ObjectResolver\DataObject;
use Symfony\Component\Validator\Constraints as Assert;

class FeedbackData implements DataObject
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public $text;
}
