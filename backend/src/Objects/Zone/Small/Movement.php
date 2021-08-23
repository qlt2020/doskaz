<?php


namespace App\Objects\Zone\Small;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Adding\Attribute;
use App\Objects\AttributesConfiguration;
use App\Objects\Zone;

class Movement extends Zone
{
    protected static function attributesKeys(): array
    {
        return AttributesConfiguration::getAttributesKeysForFormAndZone('small', 'movement');
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

        if ($this->isMatches([1], Attribute::yes()) && $this->isMatchesAllExcept([1], Attribute::notProvided())) {
            return AccessibilityScore::fullAccessible();
        }

        $builder = AccessibilityScoreBuilder::initPartialAccessible();

        if ($this->isMatches([1000, 1001], Attribute::no())) {
            $builder->withMovementNotAccessible();
        }

        if (($this->isMatchesPartial([1, 6, 7, 1000, 1001], Attribute::yes()) || $this->isMatchesPartial([1, 6, 7, 1000, 1001], Attribute::unknown())) && !$this->isMatchesPartial([1, 6, 7, 1000, 1001], Attribute::no())) {
            $builder->withMovementFullAccessible();
        }

        if ($this->isMatches([1, 6, 7], Attribute::yes())) {
            $builder->withLimbFullAccessible()
                ->withVisionFullAccessible()
                ->withIntellectualFullAccessible()
                ->withHearingFullAccessible();
        }

        if ($this->isMatches([1, 6, 7], Attribute::yes()) && $this->isMatches([1000, 1001], Attribute::notProvided())) {
            $builder->withMovementFullAccessible();
        }

        if ($this->isMatches([1, 6, 7, 1000], Attribute::yes()) && $this->isMatches([1001], Attribute::notProvided())) {
            $builder->withMovementFullAccessible();
        }
        if ($this->isMatches([1, 6, 7, 1001], Attribute::yes()) && $this->isMatches([1000], Attribute::notProvided())) {
            $builder->withMovementFullAccessible();
        }
        if ($this->isMatches([1000], Attribute::yes()) && ($this->isMatches([1001], Attribute::unknown()) || $this->isMatches([1001], Attribute::no()))) {
            $builder->withMovementPartialAccessible();
        }
        if ($this->isMatches([1001], Attribute::yes()) && ($this->isMatches([1000], Attribute::unknown()) || $this->isMatches([1001], Attribute::no()))) {
            $builder->withMovementPartialAccessible();
        }

        return $builder->build();
    }
}
