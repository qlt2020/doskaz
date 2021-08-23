<?php


namespace App\Objects;

use App\Objects\Adding\AccessibilityScore;
use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

/**
 * @ODM()
 * @DiscriminatorMap(
 *     typeProperty="type",
 *     mapping={
 *         "small" = "App\Objects\Zone\Small\SmallFormZones",
 *         "middle" = "App\Objects\Zone\Middle\MiddleFormZones",
 *         "full" = "App\Objects\Zone\Full\FullFormZones",
 *     }
 * )
 */
abstract class Zones
{
    public function overallScore(): AccessibilityScore
    {
        $entrance = AccessibilityScore::average(
            $this->entrance1->accessibilityScore(),
            $this->entrance2 ? $this->entrance2->accessibilityScore() : null,
            $this->entrance3 ? $this->entrance3->accessibilityScore() : null,
        );

        $serviceAccessibilityScore = $this->serviceAccessibility->accessibilityScore();

        $average = AccessibilityScore::average(
            $this->parking->accessibilityScore(),
            $entrance,
            $this->movement->accessibilityScore(),
            $this->service->accessibilityScore(),
            $this->toilet->accessibilityScore(),
            $this->navigation->accessibilityScore(),
            $serviceAccessibilityScore,
            $this->kidsAccessibility->accessibilityScore()
        );

        $builder = AccessibilityScoreBuilder::initUnknown();

        foreach (AccessibilityScore::SCORE_CATEGORIES as $category) {
            if ($entrance->{$category} === AccessibilityScore::SCORE_NOT_ACCESSIBLE || $serviceAccessibilityScore->{$category} === AccessibilityScore::SCORE_NOT_ACCESSIBLE) {
                $builder->withCategoryNotAccessible($category);
            } else {
                $builder->withCategoryScore($category, $average->{$category});
            }
        }

        return $builder->build();
    }
}
