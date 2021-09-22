<?php


namespace App\Blog\Comments;

use App\Blog\Posts\Post;
use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Items;

class CommentController extends AbstractController
{
    /**
     * @Route(path="/api/blog/posts/{id}/comments", requirements={"id" = "\d+"}, methods={"GET"})
     * @param Post $post
     * @param Connection $connection
     * @param Request $request
     * @return CommentsListData
     * @Get(
     *     path="/api/blog/posts/{id}/comments",
     *     tags={"Блог"},
     *     summary="Комментарии к посту",
     *     @Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id записи блога",
     *          @Schema(type="integer", format="int32")
     *     ),
     *     @Parameter(
     *          name="sortBy",
     *          in="query",
     *          required=false,
     *          description="Параметр сортировки",
     *          example="createdAt",
     *          @Schema(type="string", enum={"popularity", "createdAt"})
     *     ),
     *     @Parameter(
     *          name="sortOrder",
     *          in="query",
     *          required=false,
     *          description="Направление сортировки",
     *          example="desc",
     *          @Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(ref="#/components/schemas/CommentsList")
     *         ),
     *         @Response(response="404", description="Post not found")
     *     }
     * )
     */
    public function commentsListController(Post $post, Connection $connection, Request $request): CommentsListData
    {
        $comments = $connection->createQueryBuilder()
            ->select([
                'blog_comments.id',
                'blog_comments.id',
                'blog_comments.user_id AS "userId"',
                'blog_comments.text',
                'blog_comments.created_at AS "createdAt"',
                'blog_comments.parent_id AS "parentId"',
                'users.full_name->>\'firstAndLast\' as "userName"'
            ])
            ->from('blog_comments')
            ->leftJoin('blog_comments', 'users', 'users', 'users.id = blog_comments.user_id')
            ->andWhere('post_id = :postId')
            ->andWhere('is_published = true')
            ->setParameter('postId', $post->id())
            ->orderBy(sprintf('"%s"', $request->query->get('sortBy', 'createdAt')), $request->query->get('sortOrder', 'desc'))
            ->execute()
            ->fetchAll(\PDO::FETCH_UNIQUE);

        foreach ($comments as $id => &$comment) {
            if ($comment['parentId']) {
                $comments[$comment['parentId']]['replies'] = array_merge($comments[$comment['parentId']]['replies'] ?? [], [&$comment]);
            }
        }
        unset($comment);

        $items = array_map(
            function ($comment) use ($connection) {
                return CommentData::fromArray($comment, $connection);
            },
            array_values(
                array_filter($comments, function ($comment) {
                    return is_null($comment['parentId']);
                })
            )
        );

        return new CommentsListData(count($comments), $items);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/api/blog/posts/{id}/comments", requirements={"id" = "\d+"}, methods={"POST"})
     * @param Post $post
     * @param CommentData $commentData
     * @param CommentRepository $commentRepository
     * @param Flusher $flusher
     * @\OpenApi\Annotations\Post(
     *     path="/api/blog/posts/{id}/comments",
     *     tags={"Блог"},
     *     summary="Создать комментарий к посту",
     *     security={{"clientAuth": {}}},
     *     @Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="id записи блога",
     *          @Schema(type="integer", format="int32")
     *     ),
     *     @RequestBody(
     *         required=true,
     *         description="",
     *         @JsonContent(ref="#/components/schemas/Comment")
     *     ),
     *     responses={
     *         @Response(response="204", description="No content"),
     *         @Response(response="404", description="Post not found"),
     *         @Response(response="401", description="Unauthorized"),
     *         @Response(response="400", description="Bad request"),
     *     }
     * )
     */
    public function reply(Post $post, CommentData $commentData, CommentRepository $commentRepository, Flusher $flusher)
    {
        if ($commentData->parentId) {
            $parentComment = $commentRepository->findOneBy([
                'id' => $commentData->parentId,
                'postId' => $post->id()
            ]);
            if (!$parentComment) {
                throw new NotFoundHttpException();
            }
            $parentComment->increasePopularity();
        }

        $comment = new Comment($post->id(), $this->getUser()->id(), $commentData->text, $commentData->parentId);
        $commentRepository->add($comment);
        $flusher->flush();
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/comments/update/{id}", methods={"POST"})
     * @param Comment $comment
     * @param CommentData $data
     * @param Flusher $flusher
     * @return JsonResponse
     * @\OpenApi\Annotations\Post(
     *     path="/api/admin/comments/update/{id}",
     *     @Parameter(name="id", in="path", required=true, description="id комментария", @Schema(type="string"), example="58a5d23c-2b17-47b2-b5a7-fca7b40e1044"),
     *     summary="Модерация комментария к новости",
     *     security={{"clientAuth": {}}},
     *     tags={"Комментарии"},
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="text", type="string", description="Текст комментария")
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
    public function update(Comment $comment, CommentData $data, Flusher $flusher): JsonResponse
    {
        $comment->update($data);
        $flusher->flush();

        return new JsonResponse('Comment has been modified', 200);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/comments/accept/{id}", methods={"GET"})
     * @param Comment $comment
     * @param Flusher $flusher
     * @return JsonResponse
     * @Get(
     *     path="/api/admin/comments/accept/{id}",
     *     @Parameter(name="id", in="path", required=true, description="id комментария", @Schema(type="string"), example="58a5d23c-2b17-47b2-b5a7-fca7b40e1044"),
     *     summary="Принятие отзыва к объекту",
     *     tags={"Комментарии"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     */
    public function accept(Comment $comment, Flusher $flusher): JsonResponse
    {
        $comment->accept();
        $flusher->flush();

        return new JsonResponse('The review has been accepted', 200);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/comments/decline/{id}", methods={"GET"})
     * @param Comment $comment
     * @param CommentRepository $repository
     * @param Flusher $flusher
     * @return JsonResponse
     * @Get(
     *     path="/api/admin/comments/decline/{id}",
     *     @Parameter(name="id", in="path", required=true, description="id комментария", @Schema(type="string"), example="58a5d23c-2b17-47b2-b5a7-fca7b40e1044"),
     *     summary="Отклонение комментария к объекту",
     *     tags={"Комментарии"},
     *     security={{"clientAuth": {}}},
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     */
    public function decline(Comment $comment, CommentRepository $repository, Flusher $flusher): JsonResponse
    {
        $repository->delete($comment);
        $flusher->flush();

        return new JsonResponse('The comment has been declined', 200);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/api/admin/comments", methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array
     * @throws \Doctrine\DBAL\Exception
     * @Get(
     *     path="/api/admin/comments",
     *     @Parameter(name="is_published", in="query", required=false, description="Опубликован", @Schema(type="boolean")),
     *     summary="Комментарии к новостям",
     *     security={{"clientAuth": {}}},
     *     tags={"Комментарии"},
     *     @Response(response=404, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=204, description=""),
     * )
     */
    public function list(Request $request, Connection $connection): array
    {
        $query = $connection->createQueryBuilder()->from('blog_comments')
            ->leftJoin('blog_comments', 'blog_posts', 'posts', 'blog_comments.post_id = posts.id')
            ->leftJoin('blog_comments', 'users', 'authors', 'authors.id = blog_comments.user_id')
            ->select([
                'blog_comments.id',
                'blog_comments.is_published',
                'blog_comments.text',
                'posts.title as post',
                'posts.id as post_id',
                'authors.full_name->>\'firstAndLast\' as author'
            ])
            ->andWhere('posts.deleted_at is NULL')
            ->orderBy('blog_comments.created_at', 'desc');

        if ($request->query->has('is_published')) {
            $query->andWhere("blog_comments.is_published = {$request->query->getAlpha('is_published')}");
        }

        return $query->execute()->fetchAllAssociative();
    }
}
