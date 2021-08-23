<?php


namespace App\Objects\Zone\Small;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Parking extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('small', 'parking');
    }

    public function calculateScore(): AccessibilityScore
    {
        $builder = AccessibilityScoreBuilder::initPartialAccessible()->withHearingFullAccessible();

        if ($this->isMatches([1], Attribute::yes())) {
            return AccessibilityScore::fullAccessible();
        }

        if ($this->isMatches([1], Attribute::notProvided())) {
            return AccessibilityScore::notProvided();
        }

        if ($this->isMatches([1], Attribute::unknown())) {
            return AccessibilityScore::unknown();
        }

        return $builder->build();
    }
}
