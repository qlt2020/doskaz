<?php


namespace App\Objects\Zone\Full;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Movement extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('full', 'movement');
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

        if (!$this->isMatches([32, 70, 34, 35, 55, 56, 57], Attribute::yes())) {
            $builder->withMovementNotAccessible();
        }

        if ($this->isMatchesAllExcept([22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 53, 54, 70, 34, 35, 55, 56, 57, 36, 58, 60, 61, 62, 63], Attribute::yes())) {
            $builder->withHearingFullAccessible()
                ->withIntellectualFullAccessible()
                ->withLimbFullAccessible();
        }

        if ($this->isMatchesAllExcept([22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 53, 54, 70, 34, 35, 55, 56, 57, 60, 61, 62, 63], Attribute::yes())) {
            $builder->withVisionFullAccessible();
        }

        if ($this->isMatchesPartial([32, 70, 34, 35, 55, 56, 57], Attribute::yes())) {
            $builder->withMovementPartialAccessible();
        }

        if ($this->isMatchesAllExcept([36, 58, 12, 13, 14, 15, 16, 17, 18], Attribute::yes())) {
            $builder->withMovementFullAccessible();
        }

        return $builder->build();
    }
}
