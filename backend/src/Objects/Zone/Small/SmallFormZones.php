<?php


namespace App\Objects\Zone\Small;

use App\Objects\AccessibilityScoreBuilder;
use App\Objects\Adding\AccessibilityScore;
use App\Objects\Zones;

class SmallFormZones extends Zones
{
    /**
     * @var Parking
     */
    public $parking;

    /**
     * @var Entrance
     */
    public $entrance1;

    /**
     * @var null
     */
    public $entrance2 = null;

    /**
     * @var null
     */
    public $entrance3 = null;

    /**
     * @var Movement
     */
    public $movement;

    /**
     * @var Service
     */
    public $service;

    /**
     * @var Toilet
     */
    public $toilet;

    /**
     * @var Navigation
     */
    public $navigation;

    /**
     * @var ServiceAccessibility
     */
    public $serviceAccessibility;

    public KidsAccessibility $kidsAccessibility;

    /**
     * SmallFormZones constructor.
     * @param Parking $parking
     * @param Entrance $entrance1
     * @param Movement $movement
     * @param Service $service
     * @param Toilet $toilet
     * @param Navigation $navigation
     * @param ServiceAccessibility $serviceAccessibility
     * @param KidsAccessibility|null $kidsAccessibility
     */
    public function __construct(Parking $parking, Entrance $entrance1, Movement $movement, Service $service, Toilet $toilet, Navigation $navigation, ServiceAccessibility $serviceAccessibility, ?KidsAccessibility $kidsAccessibility = null)
    {
        $this->parking = $parking;
        $this->entrance1 = $entrance1;
        $this->movement = $movement;
        $this->service = $service;
        $this->toilet = $toilet;
        $this->navigation = $navigation;
        $this->serviceAccessibility = $serviceAccessibility;
        $this->kidsAccessibility = $kidsAccessibility ?? new KidsAccessibility();
    }

    public function overallScore(): AccessibilityScore
    {
        $entrance1AccessibilityScore = $this->entrance1->accessibilityScore();
        $serviceAccessibilityScore = $this->serviceAccessibility->accessibilityScore();

        $average = AccessibilityScore::average(
            $this->parking->accessibilityScore(),
            $entrance1AccessibilityScore,
            $this->movement->accessibilityScore(),
            $this->service->accessibilityScore(),
            $this->toilet->accessibilityScore(),
            $this->navigation->accessibilityScore(),
            $serviceAccessibilityScore,
            $this->kidsAccessibility->accessibilityScore()
        );

        $builder = AccessibilityScoreBuilder::initUnknown();

        foreach (AccessibilityScore::SCORE_CATEGORIES as $category) {
            if ($entrance1AccessibilityScore->{$category} === AccessibilityScore::SCORE_NOT_ACCESSIBLE || $serviceAccessibilityScore->{$category} === AccessibilityScore::SCORE_NOT_ACCESSIBLE) {
                $builder->withCategoryNotAccessible($category);
            } else {
                $builder->withCategoryScore($category, $average->{$category});
            }
        }

        return $builder->build();
    }
}
