<?php


namespace App\Objects\Zone\Full;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Navigation extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('full', 'navigation');
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

        if ($this->isMatches([1, 2, 3, 4, 8, 9, 10, 11, 12, 13, 14], Attribute::yes())) {
            $builder->withHearingFullAccessible();
        }
        if ($this->isMatches([1, 2, 3, 4, 8, 9, 10, 11, 12, 5, 16, 6, 17, 18, 19, 20, 21], Attribute::yes())) {
            $builder->withVisionFullAccessible();
        }
        if ($this->isMatches([1, 2, 3, 4, 8, 9, 10, 11, 12, 15], Attribute::yes())) {
            $builder->withIntellectualFullAccessible();
        }
        if ($this->isMatches([1, 2, 3, 4, 8, 9, 10, 11, 12], Attribute::yes())) {
            $builder->withMovementFullAccessible()
                ->withLimbFullAccessible();
        }

        if ($this->isMatches([1, 2, 3, 4, 8, 9, 10, 11, 12], Attribute::no())) {
            $builder->withMovementNotAccessible()
                ->withLimbNotAccessible();

            if ($this->isMatches([15], Attribute::no())) {
                $builder->withIntellectualNotAccessible();
            }

            if ($this->isMatches([13, 14], Attribute::no())) {
                $builder->withHearingNotAccessible();
            }

            if ($this->isMatches([5, 16, 6, 17, 18, 19, 20, 21], Attribute::no())) {
                $builder->withVisionNotAccessible();
            }
        }

        if ($this->isMatches([1, 2, 3, 4, 8, 9, 10, 11, 12], Attribute::yes())) {
            if ($this->isMatchesPartial([13, 14], Attribute::yes()) && $this->isMatchesPartial([13, 14], Attribute::notProvided())) {
                $builder->withHearingFullAccessible();
            }
        }

        return $builder->build();
    }
}
