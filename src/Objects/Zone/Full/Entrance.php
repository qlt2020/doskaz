<?php


namespace App\Objects\Zone\Full;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Entrance extends Zone
{
    private const INDEX_REMAP = [
        1 => 41,
        2 => 42,
        3 => 43,
        4 => 1,
        5 => 2,
        6 => 3,
        7 => 4,
        8 => 5,
        9 => 6,
        10 => 7,
        11 => 8,
        12 => 9,
        13 => 10,
        14 => 11,
        15 => 12,
        16 => 13,
        17 => 14,
        18 => 15,
        19 => 44,
        20 => 45,
        21 => 16,
        22 => 46,
        23 => 47,
        24 => 17,
        25 => 18,
        26 => 19,
        27 => 20,
        28 => 21,
        29 => 48,
        30 => 49,
        31 => 50,
        32 => 22,
        33 => 51,
        34 => 23,
        35 => 24,
        36 => 25,
        37 => 26,
        38 => 52,
        39 => 27,
        40 => 28,
        41 => 53,
        42 => 29,
        43 => 30,
        44 => 31,
        45 => 54,
        46 => 55,
        47 => 56,
        48 => 57,
        49 => 58,
        50 => 32,
        51 => 33,
        52 => 34,
        53 => 35,
        54 => 36,
        55 => 37,
        56 => 38,
        57 => 39,
        58 => 40,
    ];

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
            ->withMovementNotAccessible();

        if ($this->isMatches([1], Attribute::yes())) {
            $builder->withLimbFullAccessible()
                ->withVisionFullAccessible()
                ->withHearingFullAccessible()
                ->withMovementFullAccessible()
                ->withIntellectualFullAccessible();
        }

        if (!$this->isMatches([1], Attribute::no()) && $this->isMatchesPartial([18, 60, 51, 23, 24, 25], Attribute::yes())) {
            $builder->withMovementPartialAccessible();
        }

        if ($this->isMatches([1], Attribute::no()) && $this->isMatches([2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())) {
            $builder->withLimbFullAccessible()
                ->withHearingFullAccessible()
                ->withIntellectualFullAccessible();
        }
        if ($this->isMatches([1], Attribute::no()) && $this->isMatches([2, 3, 4, 5, 6, 7, 8, 9, 10, 32, 34, 35], Attribute::yes())) {
            $builder->withVisionFullAccessible();
        }

        if ($this->isMatches([1], Attribute::no()) && $this->isMatchesAllExcept([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 32, 34, 35], Attribute::yes())) {
            $builder->withMovementFullAccessible();
        }


        return $builder->build();
    }

    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('full', 'entrance');
    }
}
