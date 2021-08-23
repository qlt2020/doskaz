<?php


namespace App\Objects\Adding;

use App\Infrastructure\Doctrine\Flusher;
use App\Objects\Zone;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/objects")
 * @IsGranted("ROLE_USER")
 */
class AddingController extends AbstractController
{
    /**
     * @Route(path="/requests", methods={"POST"})
     * @param Form $addingRequestData
     * @param AddingRequestRepository $addingRequestRepository
     * @param Flusher $flusher
     * @return void
     * @Post(
     *     path="/api/objects/requests",
     *     tags={"Запросы на добавление объектов"},
     *     security={{"clientAuth": {}}},
     *     summary="Отправка запроса на добавление объекта",
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="form", type="string", enum={"small", "middle", "full"}, description="Вид формы (простая, средняя, сложная)"),
     *             @Property(
     *                 property="first",
     *                 type="object",
     *                 description="Общая информация",
     *                 @Property(property="name", type="string", description="Наименование"),
     *                 @Property(property="description", type="string", description="Описание"),
     *                 @Property(property="otherNames", type="string", description="Другие наименования"),
     *                 @Property(property="address", type="string", description="Адрес"),
     *                 @Property(property="categoryId", type="integer", description="Id подкатегории"),
     *                 @Property(property="point", type="array", @Items(type="number", format="float"), example={52.253724266066, 76.9443852187141}, description="Координаты"),
     *                 @Property(property="videos", type="array", @Items(type="string"), description="Ссылки на видео", example={}),
     *                 @Property(property="photos", type="array", @Items(type="string"), description="Ссылки на фото", example={}),
     *             ),
     *             @Property(
     *                 property="parking",
     *                 description="Зона парковки",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="entrance1",
     *                 description="Входная группа 1",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="entrance2",
     *                 description="Входная группа 2",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="entrance3",
     *                 description="Входная группа 3",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="movement",
     *                 description="Пути передвижения",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="service",
     *                 description="Зона оказания услуги",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="toilet",
     *                 description="Туалет",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="navigation",
     *                 description="Навигация",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="serviceAccessibility",
     *                 description="Доступность услуги",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="kidsAccessibility",
     *                 description="Доступность и безопасность услуг для детей до 7 лет",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             )
     *         )
     *     ),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description="")
     * )
     */
    public function add(Form $addingRequestData, AddingRequestRepository $addingRequestRepository, Flusher $flusher)
    {
        $request = new AddingRequest($this->getUser()->id(), $addingRequestData);
        $addingRequestRepository->add($request);
        $flusher->flush();
    }

    /**
     * @Route(path="/requests/validate", methods={"POST"})
     * @param Form $addingRequestData
     * @Post(
     *     path="/api/objects/requests/validate",
     *     tags={"Запросы на добавление объектов"},
     *     security={{"clientAuth": {}}},
     *     summary="Валидация запроса на добавление объекта",
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="form", type="string", enum={"small", "middle", "full"}, description="Вид формы (простая, средняя, сложная)"),
     *             @Property(
     *                 property="first",
     *                 type="object",
     *                 description="Общая информация",
     *                 @Property(property="name", type="string", description="Наименование"),
     *                 @Property(property="description", type="string", description="Описание"),
     *                 @Property(property="otherNames", type="string", description="Другие наименования"),
     *                 @Property(property="address", type="string", description="Адрес"),
     *                 @Property(property="categoryId", type="integer", description="Id подкатегории"),
     *                 @Property(property="point", type="array", @Items(type="number", format="float"), example={52.253724266066, 76.9443852187141}, description="Координаты"),
     *                 @Property(property="videos", type="array", @Items(type="string"), description="Ссылки на видео", example={}),
     *                 @Property(property="photos", type="array", @Items(type="string"), description="Ссылки на фото", example={}),
     *             ),
     *             @Property(
     *                 property="parking",
     *                 description="Зона парковки",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="entrance1",
     *                 description="Входная группа 1",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="entrance2",
     *                 description="Входная группа 2",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="entrance3",
     *                 description="Входная группа 3",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="movement",
     *                 description="Пути передвижения",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="service",
     *                 description="Зона оказания услуги",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="toilet",
     *                 description="Туалет",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="navigation",
     *                 description="Навигация",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="serviceAccessibility",
     *                 description="Доступность услуги",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             ),
     *             @Property(
     *                 property="kidsAccessibility",
     *                 description="Доступность и безопасность услуг для детей до 7 лет",
     *                 type="object",
     *                 @Property(
     *                     property="attributes",
     *                     type="object",
     *                     description="Аттрибуты зоны",
     *                     @Property(
     *                         type="object",
     *                         property="attribute1",
     *                         type="string",
     *                         enum=App\Objects\Adding\Attribute::ATTRIBUTES
     *                     )
     *                 ),
     *                 @Property(property="comment", type="string", description="Комментарий к зоне")
     *             )
     *         )
     *     ),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description="")
     * )
     */
    public function validate(Form $addingRequestData)
    {
    }

    /**
     * @Route(path="/calculateZoneScore", methods={"POST"})
     * @param Zone $zone
     * @return AccessibilityScore
     * @Post(
     *     path="/api/objects/calculateZoneScore",
     *     tags={"Объекты"},
     *     summary="Рассчет оценки доступности для зоны",
     *     security={{"clientAuth": {}}},
     *     @Response(response=401, description=""),
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(
     *                 property="type",
     *                 type="string",
     *                 enum={
     *                     "parking_small",
     *                     "entrance_small",
     *                     "movement_small",
     *                     "service_small",
     *                     "toilet_small",
     *                     "navigation_small",
     *                     "accessibility_small",
     *                     "serviceAccessibility_small",
     *                     "kidsAccessibility_small",
     *                     "parking_middle",
     *                     "entrance_middle",
     *                     "toilet_middle",
     *                     "service_middle",
     *                     "accessibility_middle",
     *                     "serviceAccessibility_middle",
     *                     "kidsAccessibility_middle",
     *                     "movement_middle",
     *                     "navigation_middle",
     *                     "parking_full",
     *                     "entrance_full",
     *                     "movement_full",
     *                     "service_full",
     *                     "toilet_full",
     *                     "navigation_full",
     *                     "serviceAccessibility_full",
     *                     "accessibility_full",
     *                     "kidsAccessibility_full",
     *                 }
     *             ),
     *             @Property(
     *                 property="attributes",
     *                 type="object",
     *                 @Property(type="string", property="attribute1", enum=App\Objects\Adding\Attribute::ATTRIBUTES)
     *             )
     *         )
     *     ),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="movement", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, description="Оценка для людей передвигающихся на кресле-коляске"),
     *             @Property(property="limb", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, description="Оценка для людей с проблемами опорно-двигательного аппарата"),
     *             @Property(property="vision", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, description="Оценка для людей с проблемами зрения"),
     *             @Property(property="hearing", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, description="Оценка для людей с проблемами слуха"),
     *             @Property(property="intellectual", type="string", enum=App\Objects\Adding\AccessibilityScore::SCORE_VARIANTS, description="Оценка для людей с расстройством интеллекта"),
     *         )
     *     ),
     * )
     */
    public function calculateZoneScore(Zone $zone): AccessibilityScore
    {
        return $zone->accessibilityScore();
    }
}
