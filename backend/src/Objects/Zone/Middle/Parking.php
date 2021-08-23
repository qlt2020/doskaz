<?php


namespace App\Objects\Zone\Middle;

use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Parking extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'parking');
    }

    public function calculateScore(): AccessibilityScore
    {
        if ($this->isMatchesAll(Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }

        if ($this->isMatchesAll(Attribute::no())) {
            return AccessibilityScore::notAccessible();
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
        $hearing = AccessibilityScore::SCORE_FULL_ACCESSIBLE;
        $intellectual = AccessibilityScore::SCORE_PARTIAL_ACCESSIBLE;

        return AccessibilityScore::new($movement, $limb, $vision, $hearing, $intellectual);
    }
}
