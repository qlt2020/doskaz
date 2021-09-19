<?php
declare(strict_types=1);

namespace App\Blog\Posts;

use App\Blog\Categories\CategoryRepository;
use Doctrine\DBAL\Connection;
use Laminas\Feed\Writer\Entry;
use Laminas\Feed\Writer\Feed;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/blog/posts")
 */
final class PostsController extends AbstractController
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @param PostsFinder $postsFinder
     * @return array
     * @Get(
     *     path="/api/blog/posts",
     *     tags={"Блог"},
     *     summary="Записи блога",
     *     @Parameter(name="categoryId", in="query", description="id категории", @Schema(type="integer")),
     *     @Parameter(name="page", in="query", description="Номер страницы", @Schema(type="integer")),
     *     @Parameter(name="period", in="query", description="Период времени", @Schema(type="string", enum={"year", "month", "week"})),
     *     @Parameter(name="post_date_from", in="query", description="Дата публикации от", @Schema(type="string")),
     *     @Parameter(name="post_date_to", in="query", description="Дата публлиации до", @Schema(type="string")),
     *     @Parameter(name="search", in="query", description="Текст для поиска", @Schema(type="string")),
     *     @\OpenApi\Annotations\Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="pages", type="integer", description="Количество страниц"),
     *             @Property(
     *                 property="items",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="id", type="integer"),
     *                     @Property(property="title", type="string"),
     *                     @Property(property="annotation", type="string"),
     *                     @Property(property="content", type="string"),
     *                     @Property(property="categoryTitle", type="string"),
     *                     @Property(property="publishedAt", type="string", format="date-time"),
     *                     @Property(property="image", type="string"),
     *                     @Property(property="previewImage", type="string"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function listPosts(Request $request, CategoryRepository $categoryRepository, PostsFinder $postsFinder)
    {
        if ($request->query->has('category') && !$categoryRepository->existsBySlug($request->query->get('category'))) {
            throw new NotFoundHttpException();
        }
        if ($request->query->has('categoryId') && !$categoryRepository->find($request->query->getInt('categoryId'))) {
            throw new NotFoundHttpException();
        }
        return $postsFinder->find($request->query->all(), $request->query->getInt('page', 1), PostsFinder::ITEMS_PER_PAGE, $request->getLocale());
    }

    /**
     * @Route(path="/bySlug/{slug}", methods={"GET"})
     * @param string $slug
     * @param PostsFinder $postsFinder
     * @return array
     */
    public function bySlug(string $slug, PostsFinder $postsFinder, Request $request)
    {
        $post = $postsFinder->findOneBySlug($slug, $request->getLocale());
        if (!$post) {
            throw new NotFoundHttpException();
        }
        return $post;
    }

    /**
     * @Route(path="/{id}", methods={"GET"}, requirements={"id" = "\d+"})
     * @param string $slug
     * @param PostsFinder $postsFinder
     * @return array
     * @Get(
     *     path="/api/blog/posts/{id}",
     *     tags={"Блог"},
     *     summary="Получение записи в блоге по id",
     *     @Parameter(name="id", in="path", description="Id записи", @Schema(type="integer"), required=true),
     *     @\OpenApi\Annotations\Response(response=404, description=""),
     *     @\OpenApi\Annotations\Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(
     *                 property="post",
     *                 type="object",
     *                 @Property(property="id", type="integer"),
     *                 @Property(property="title", type="string"),
     *                 @Property(property="annotation", type="string"),
     *                 @Property(property="categoryTitle", type="string"),
     *                 @Property(property="publishedAt", type="string", format="date-time"),
     *                 @Property(property="image", type="string"),
     *                 @Property(property="previewImage", type="string"),
     *             ),
     *             @Property(
     *                 property="similar",
     *                 description="Похожие записи",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="id", type="integer"),
     *                     @Property(property="categoryId", type="integer"),
     *                     @Property(property="title", type="string"),
     *                     @Property(property="image", type="string"),
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function byId($id, PostsFinder $postsFinder, Request $request)
    {
        $post = $postsFinder->findOneById($id, $request->getLocale());
        if (!$post) {
            throw new NotFoundHttpException();
        }
        return $post;
    }

    /**
     * @Route(path="/rss", methods={"GET"})
     * @param Request $request
     * @param PostsFinder $postsFinder
     * @return Response
     */
    public function rss(Request $request, PostsFinder $postsFinder)
    {
        $feed = new Feed();
        $feed->setTitle('Доступный Казахстан - Блог');
        $feed->setDescription('Доступный Казахстан - Блог');
        $feed->setLink($request->getSchemeAndHttpHost());

        $posts = $postsFinder->find();
        foreach ($posts['items'] as $post) {
            $entry = new Entry();
            $entry->setTitle($post['title']);
            $entry->setDateCreated($post['publishedAt']);
            $entry->setDescription($post['annotation']);
            $entry->setContent($post['content']);
            $entry->setLink($request->getSchemeAndHttpHost() . '/blog/' . $post['categorySlug'] . '/' . $post['slug']);
            $feed->addEntry($entry);
        }

        return new Response($feed->export('rss'), 200, ['Content-Type' => 'application/rss+xml']);
    }

    /**
     * @Route(path="/bySlug/{categorySlug}/{postSlug}", methods={"GET"})
     * @param string $categorySlug
     * @param string $postSlug
     * @return array
     */
    public function findPostBySlug(string $categorySlug, string $postSlug)
    {
        $post = $this->connection->createQueryBuilder()
            ->select('slug_value as slug')
            ->addSelect('blog_categories.slug_value as category_slug')
            ->addSelect('title')
            ->addSelect('content')
            ->addSelect('image')
            ->addSelect('blog_categories.title as category_title')
            ->from('blog_posts')
            ->andWhere('blog_posts.deleted_at IS NULL AND blog_posts.is_published = true')
            ->andWhere('blog_posts.published_at <= CURRENT_TIMESTAMP')
            ->andWhere('slug_value = :postSlug')
            ->setParameter('postSlug', $postSlug)
            ->andwhere('blog_categories.slug_value = :categorySlug')
            ->setParameter('categorySlug', $categorySlug)
            ->join('blog_posts', 'blog_categories', 'blog_posts.category_id = blog_categories.id')
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        if (!$post) {
            throw new NotFoundHttpException();
        }

        return [
            'title' => $post['title'],
            'content' => $post['content'],
            'categoryTitle' => $post['category_title']
        ];
    }
}
