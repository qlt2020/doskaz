<?php


namespace App\Objects\Zone\Small;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Entrance extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('small', 'entrance');
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

        $builder = AccessibilityScoreBuilder::initPartialAccessible();

        if ($this->isMatches([1], Attribute::yes()) || $this->isMatches([30, 31, 1002], Attribute::yes())) {
            $builder->withVisionFullAccessible()
                ->withLimbFullAccessible()
                ->withHearingFullAccessible()
                ->withIntellectualFullAccessible();
        }

        if ($this->isMatches([1, 30, 31], Attribute::yes()) || $this->isMatches([1000, 1001, 30, 31, 1002], Attribute::yes())) {
            $builder->withMovementFullAccessible();
        } elseif ($this->isMatches([1, 1000, 1001], Attribute::no())) {
            $builder->withMovementNotAccessible();
        }

        return $builder->build();
    }
}
