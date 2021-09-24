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
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations\Get;

class ReviewController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
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

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/reviews/update/{id}", methods={"POST"})
     * @param Review $review
     * @param ReviewData $reviewData
     * @param Flusher $flusher
     * @Post(
     *     path="/api/admin/reviews/update/{id}",
     *     @Parameter(name="id", in="path", required=true, description="id отзыва", @Schema(type="string"), example="58a5d23c-2b17-47b2-b5a7-fca7b40e1044"),
     *     summary="Модерация отзыва к объекту",
     *     tags={"Комментарии"},
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
     * @return JsonResponse
     */
    public function update(Review $review, ReviewData $reviewData, Flusher $flusher): JsonResponse
    {
        $review->update($reviewData);
        $flusher->flush();

        return new JsonResponse('Review has been modified', 200);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/reviews/accept/{id}", methods={"GET"})
     * @param Review $review
     * @param Flusher $flusher
     * @return JsonResponse
     * @Get(
     *     path="/api/admin/reviews/accept/{id}",
     *     @Parameter(name="id", in="path", required=true, description="id отзыва", @Schema(type="string"), example="58a5d23c-2b17-47b2-b5a7-fca7b40e1044"),
     *     summary="Принятие отзыва к объекту",
     *     tags={"Комментарии"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     */
    public function accept(Review $review, Flusher $flusher): JsonResponse
    {
        $review->accept();
        $flusher->flush();

        return new JsonResponse('The review has been accepted', 200);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/reviews/decline/{id}", methods={"GET"})
     * @param Review $review
     * @param Flusher $flusher
     * @Get(
     *     path="/api/admin/reviews/decline/{id}",
     *     @Parameter(name="id", in="path", required=true, description="id отзыва", @Schema(type="string"), example="58a5d23c-2b17-47b2-b5a7-fca7b40e1044"),
     *     summary="Отклонение отзыва к объекту",
     *     tags={"Комментарии"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     * @return JsonResponse
     */
    public function decline(Review $review, Flusher $flusher): JsonResponse
    {
        $review->decline();
        $flusher->flush();

        return new JsonResponse('The review has been declined', 200);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/reviews", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array
     * @throws \Doctrine\DBAL\Exception
     * @Get(
     *     path="/api/admin/reviews",
     *     @Parameter(name="is_published", in="query", required=false, description="Опубликован", @Schema(type="boolean")),
     *     summary="Отзывы к объекту",
     *     tags={"Комментарии"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     */
    public function list(Request $request, Connection $connection): array
    {
        $query = $connection->createQueryBuilder()->from('object_reviews')
            ->leftJoin('object_reviews', 'objects', 'objects', 'object_reviews.object_id = objects.id')
            ->leftJoin('object_reviews', 'users', 'authors', 'authors.id = object_reviews.author_id')
            ->select([
                'object_reviews.id',
                'object_reviews.is_published',
                'object_reviews.text',
                'objects.title as object',
                'objects.id as object_id',
                'authors.full_name->>\'firstAndLast\' as author'
            ])
            ->andWhere('object_reviews.deleted_at is NULL')
            ->andWhere('objects.deleted_at is NULL')
            ->orderBy('object_reviews.created_at', 'desc');

        if ($request->query->has('is_published')) {
            $query->andWhere("object_reviews.is_published = {$request->query->getAlpha('is_published')}");
        }

        $results = $query->execute()->fetchAllAssociative();
        return [
            'items' => $results,
            'count' => count($results)
        ];
    }
}
