<?php


namespace App\Objects\Reviews;

use App\Infrastructure\Doctrine\Flusher;
use App\Objects\MapObject;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route(path="/api/objects/{id}/reviews", methods={"POST"})
     * @param MapObject $mapObject
     * @param ReviewData $reviewData
     * @param ReviewRepository $repository
     * @param Flusher $flusher
     * @throws \Exception
     * @Post(
     *     path="/api/objects/{id}/reviews",
     *     @Parameter(name="id", in="path", required=true, description="id объекта", @Schema(type="integer"), example=172709),
     *     summary="Создание отзыва к объекту",
     *     tags={"Объекты"},
     *     security={{"clientAuth": {}}},
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="text", type="string", description="Текст отзыва")
     *         )
     *     ),
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     *     @Response(
     *         response=400,
     *         description="Validation Failed",
     *         @JsonContent(
     *             @Property(property="message", type="string", example="Validation Failed"),
     *             @Property(property="code", type="number", example=400),
     *             @Property(
     *                 property="errors",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="property", type="string", description="Имя свойства"),
     *                     @Property(property="message", type="string", description="Текст ошибки"),
     *                 )
     *             )
     *         )
     *     ),
     * )
     */
    public function create(MapObject $mapObject, ReviewData $reviewData, ReviewRepository $repository, Flusher $flusher)
    {
        $review = new Review($mapObject->id(), $reviewData->text, $this->getUser()->id());
        $repository->add($review);
        $flusher->flush();
    }
}
