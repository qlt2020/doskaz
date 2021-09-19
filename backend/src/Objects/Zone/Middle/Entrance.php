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
        $keys = [1, 26, 27, 28, 29, 30, 31, 32, 34, 35, 36, 37];

        if ($this->isMatches($keys, Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }

        if ($this->isMatches($keys, Attribute::no())) {
            return AccessibilityScore::notAccessible();
        }

        if ($this->isMatches($keys, Attribute::notProvided())) {
            return AccessibilityScore::notProvided();
        }

        if ($this->isMatches($keys, Attribute::unknown())) {
            return AccessibilityScore::unknown();
        }

        if ($this->isMatchesPartial($keys, Attribute::yes())
            && ($this->isMatchesPartial($keys, Attribute::notProvided()) || $this->isMatchesPartial($keys, Attribute::unknown()))
            && !$this->isMatchesPartial($keys, Attribute::no())) {
            return AccessibilityScore::fullAccessible();
        }

        if ($this->isMatchesPartial($keys, Attribute::no())
            && ($this->isMatchesPartial($keys, Attribute::notProvided()) || $this->isMatchesPartial($keys, Attribute::unknown()))
            && !$this->isMatchesPartial($keys, Attribute::yes())) {
            return AccessibilityScore::notAccessible();
        }

        if (!$this->isMatchesPartial($keys, Attribute::yes()) && !$this->isMatchesPartial($keys, Attribute::no())) {
            return AccessibilityScore::notProvided();
        }

        return AccessibilityScore::partialAccessible();
    }
}
