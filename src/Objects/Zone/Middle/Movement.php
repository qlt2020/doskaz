<?php


namespace App\Objects\Zone\Middle;

use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Movement extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'movement');
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

        $movement = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $limb = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $vision = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $hearing = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;
        $intellectual = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;

        if ($this->isMatches([32, 70, 34, 35], Attribute::no()) || (!$this->isMatchesPartial([32, 70, 34, 35], Attribute::yes()) && !$this->isMatchesPartial([32, 70, 34, 35], Attribute::unknown()))) {
            $movement = AccessibilityScore::SCORE_NOT_ACCESSIBLE;
        }

        if ($this->isMatchesAllExcept([22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 70, 35, 35], Attribute::yes())) {
            $limb = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
            $hearing = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
            $intellectual = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }

        if ($this->isMatchesAllExcept([22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 70, 35], Attribute::yes())) {
            $vision = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }

        if ($this->isMatchesAllExcept([12, 13, 14, 15, 16, 17, 18, 19, 20, 21], Attribute::yes())) {
            $movement = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        }

        return AccessibilityScore::new($movement, $limb, $vision, $hearing, $intellectual);
    }
}
