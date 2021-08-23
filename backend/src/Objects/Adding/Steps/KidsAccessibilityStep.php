<?php


namespace App\Objects\Adding\Steps;

use App\Objects\Adding\AccessibilityScore;
use App\Objects\AttributesMap;
use App\Objects\Zone\Middle\ServiceAccessibility;
use Symfony\Component\Validator\Constraints as Assert;

class KidsAccessibilityStep
{
    /**
     * @Assert\NotBlank()
     * @var AttributesMap|null
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
