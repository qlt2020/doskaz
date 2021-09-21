<?php


namespace App\Objects\Zone\Middle;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Service extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('middle', 'service');
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

        if ($this->isMatchesAllExcept([4, 5], Attribute::yes())) {
            $builder->withLimbFullAccessible()
                ->withVisionFullAccessible()
                ->withHearingFullAccessible()
                ->withIntellectualFullAccessible();
        }

        if ($this->isMatchesAll(Attribute::yes())) {
            $builder->withMovementFullAccessible();
        }
        if ($this->isMatches([4, 5], Attribute::no())) {
            $builder->withMovementNotAccessible();
        }

        return $builder->build();
    }
}
