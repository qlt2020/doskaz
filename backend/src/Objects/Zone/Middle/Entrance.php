<?php


namespace App\Objects\Zone\Middle;

use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Entrance extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'entrance');
    }

    public function calculateScore(): AccessibilityScore
    {
        if ($this->isMatchesAll(Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }
        if ($this->isMatchesAll(Attribute::notProvided())) {
            return AccessibilityScore::notProvided();
        }
        if ($this->isMatchesAll(Attribute::unknown())) {
            return AccessibilityScore::unknown();
        }

        $movement = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $limb = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $vision = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $hearing = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        $intellectual = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;


        if ($this->isMatchesAll(Attribute::unknown())) {
            $movement = AccessibilityScore::SCORE_UNKNOWN;
            $limb = AccessibilityScore::SCORE_UNKNOWN;
            $vision = AccessibilityScore::SCORE_UNKNOWN;
            $intellectual = AccessibilityScore::SCORE_UNKNOWN;
        }

        if ($this->isMatches([1], Attribute::yes())) {
            $movement = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
            $limb = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
            $vision = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
            $intellectual = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }

        if ($this->isMatches([1, 18, 60, 23, 24, 25], Attribute::no()) || $this->isMatchesPartial([1, 18, 60, 23, 24, 25], Attribute::no()) || $this->isMatchesPartial([1, 18, 60, 23, 24, 25], Attribute::notProvided())) {
            $movement = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        }

        if ($this->isMatches([1], Attribute::no()) && $this->isMatchesAllExcept([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 32, 34, 35], Attribute::yes())) {
            $movement = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }

        if ($this->isMatches([1], Attribute::no()) && $this->isMatches([2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())) {
            $limb = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
            $intellectual = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }
        if ($this->isMatches([1], Attribute::no()) && $this->isMatches([2, 3, 4, 5, 6, 7, 8, 9, 10, 32, 34, 35, 36, 37], Attribute::yes())) {
            $vision = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }
        if ($this->isMatches([1], Attribute::no()) && $this->isMatchesAllExcept([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())) {
            $movement = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }
        if ($this->isMatches([1], Attribute::no()) && $this->isMatchesPartial([18, 60, 23, 24, 25], Attribute::yes())) {
            $movement = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        }


        return AccessibilityScore::new($movement, $limb, $vision, $hearing, $intellectual);
    }
}
