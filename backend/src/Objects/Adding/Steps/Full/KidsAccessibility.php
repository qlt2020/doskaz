<?php


namespace App\Objects\Adding\Steps\Full;

use App\Objects\Adding\AccessibilityScore;
use App\Objects\AttributesMap;
use Symfony\Component\Validator\Constraints as Assert;

class KidsAccessibility
{
    /**
     * @Assert\NotBlank()
     * @var AttributesMap
     */
    public $attributes;

    /**
     * @var string|null
     */
    public $comment;

    /**
     * @var AccessibilityScore|null
     */
    public $overriddenScore;
}
