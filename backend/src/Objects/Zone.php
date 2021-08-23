<?php


namespace App\Objects;

use App\Infrastructure\ObjectResolver\DataObject;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\Zone\Small\KidsAccessibility;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping=Zone::DISCRIMINATOR_MAP
 * )
 */
abstract class Zone implements DataObject
{
    public const DISCRIMINATOR_MAP = [
        'parking_small' => \App\Objects\Zone\Small\Parking::class,
        'entrance_small' => \App\Objects\Zone\Small\Entrance::class,
        'movement_small' => \App\Objects\Zone\Small\Movement::class,
        'service_small' => \App\Objects\Zone\Small\Service::class,
        'toilet_small' => \App\Objects\Zone\Small\Toilet::class,
        'navigation_small' => \App\Objects\Zone\Small\Navigation::class,
        "accessibility_small" => \App\Objects\Zone\Small\ServiceAccessibility::class,
        "serviceAccessibility_small" => \App\Objects\Zone\Small\ServiceAccessibility::class,
        'parking_middle' => \App\Objects\Zone\Middle\Parking::class,
        "entrance_middle" => \App\Objects\Zone\Middle\Entrance::class,
        "toilet_middle" => \App\Objects\Zone\Middle\Toilet::class,
        "service_middle" => \App\Objects\Zone\Middle\Service::class,
        "accessibility_middle" => \App\Objects\Zone\Middle\ServiceAccessibility::class,
        "serviceAccessibility_middle" => \App\Objects\Zone\Middle\ServiceAccessibility::class,
        "movement_middle" => \App\Objects\Zone\Middle\Movement::class,
        "navigation_middle" => \App\Objects\Zone\Middle\Navigation::class,
        "parking_full" => \App\Objects\Zone\Full\Parking::class,
        "entrance_full" => \App\Objects\Zone\Full\Entrance::class,
        "movement_full" => \App\Objects\Zone\Full\Movement::class,
        "service_full" => \App\Objects\Zone\Full\Service::class,
        "toilet_full" => \App\Objects\Zone\Full\Toilet::class,
        "navigation_full" => \App\Objects\Zone\Full\Navigation::class,
        "serviceAccessibility_full" => \App\Objects\Zone\Full\ServiceAccessibility::class,
        "accessibility_full" => \App\Objects\Zone\Full\ServiceAccessibility::class,
        "kidsAccessibility_small" => \App\Objects\Zone\Small\KidsAccessibility::class,
        "kidsAccessibility_middle" => \App\Objects\Zone\Middle\KidsAccessibility::class,
        "kidsAccessibility_full" => \App\Objects\Zone\Full\KidsAccessibility::class,
    ];

    /**
     * @var AttributesMap
     */
    public $attributes;

    /**
     * @var AccessibilityScore|null
     */
    public $overriddenScore;

    abstract protected static function attributesKeys(): array;

    public function __construct(?AttributesMap $attributes = null, ?AccessibilityScore $overriddenScore = null)
    {
        $this->attributes = new AttributesMap();
        $this->overriddenScore = $overriddenScore;

        $inputAttributes = $attributes ?? new AttributesMap();

        $defaultAttribute = Attribute::unknown();
        foreach (static::attributesKeys() as $key) {
            $this->attributes->offsetSet($key, $inputAttributes->get($key, $defaultAttribute));
        }
    }

    abstract public function calculateScore(): AccessibilityScore;

    final public function accessibilityScore(): AccessibilityScore
    {
        return $this->overriddenScore ?? $this->calculateScore();
    }

    protected function isMatches(array $keys, Attribute $compare): bool
    {
        foreach ($keys as $key) {
            if (!$this->attributes->get('attribute' . $key)->isEqualsTo($compare)) {
                return false;
            }
        }
        return true;
    }


    protected function isMatchesPartial(array $keys, Attribute $compare): bool
    {
        $matches = 0;
        foreach ($keys as $key) {
            if ($this->attributes->get('attribute' . $key)->isEqualsTo($compare)) {
                $matches++;
            }
        }
        return $matches > 0 && $matches < count($keys);
    }

    protected function isMatchesAllExcept(array $except, Attribute $compare): bool
    {
        foreach ($this->attributes as $key => $val) {
            if (in_array((int)str_replace('attribute', '', $key), $except)) {
                continue;
            }
            if (!$val->isEqualsTo($compare)) {
                return false;
            }
        }
        return true;
    }

    protected function isMatchesAll(Attribute $compare): bool
    {
        foreach ($this->attributes as $val) {
            if (!$val->isEqualsTo($compare)) {
                return false;
            }
        }
        return true;
    }
}
