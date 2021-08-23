<?php


namespace App\Objects\Zone\Middle;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class ServiceAccessibility extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'serviceAccessibility');
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

        if ($this->isMatchesAll(Attribute::no())) {
            return AccessibilityScore::partialAccessible();
        }

        if ($this->isMatchesAll(Attribute::yes()) || $this->isMatchesPartial([1, 2], Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }

        $builder = AccessibilityScoreBuilder::initPartialAccessible();

        if ($this->isMatches([1, 2], Attribute::no())) {
            $builder->withMovementNotAccessible();
        }

        return $builder->build();
    }
}
