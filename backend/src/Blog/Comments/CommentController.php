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

/**
 * @Route(path="/api/blog/posts")
 */
class CommentController extends AbstractController
{
    /**
     * @Route(path="/{id}/comments", requirements={"id" = "\d+"}, methods={"GET"})
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
     * @Route(path="/{id}/comments", requirements={"id" = "\d+"}, methods={"POST"})
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
}
