<?php


namespace App\Objects\Zone\Full;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Parking extends Zone
{
    private const INDEX_REMAP = [
        1 => 1,
        2 => 2,
        5 => 3,
        6 => 4,
        7 => 5,
        8 => 6,
        9 => 7,
        12 => 9,
        16 => 10,
        3 => 11,
        4 => 12,
        11 => 13,
        13 => 14,
        14 => 15,
        15 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
    ];

    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('full', 'parking');
    }


    private function remap(array $original)
    {
        return array_map(function ($key) {
            return self::INDEX_REMAP[$key];
        }, $original);
    }

    public function calculateScore(): AccessibilityScore
    {
        if ($this->isMatchesAll(Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }
        if ($this->isMatchesAll(Attribute::unknown())) {
            return AccessibilityScore::unknown();
        }
        if ($this->isMatchesAll(Attribute::notProvided())) {
            return AccessibilityScore::notProvided();
        }

        $builder = AccessibilityScoreBuilder::initPartialAccessible()
            ->withHearingFullAccessible();

        return $builder->build();
    }
}
