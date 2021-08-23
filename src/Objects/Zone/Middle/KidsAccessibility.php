<?php


namespace App\Objects\Zone\Middle;

use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class KidsAccessibility extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'kidsAccessibility');
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

        if ($this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())
            && $this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::notProvided())
            && !$this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::no())
        ) {
            return AccessibilityScore::fullAccessible();
        }

        if ($this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::no())
            && $this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::notProvided())
            && !$this->isMatchesPartial([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], Attribute::yes())
        ) {
            return AccessibilityScore::notAccessible();
        }

        return AccessibilityScore::partialAccessible();
    }
}
