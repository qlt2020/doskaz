<?php
declare(strict_types=1);

namespace App\Blog\Categories;

use App\Blog\Meta;
use App\Blog\MetaData;
use App\Blog\SlugFactory;
use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/blog/categories")
 */
final class CategoriesController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return CategoryData[]
     * @Get(
     *     path="/api/blog/categories",
     *     tags={"Блог"},
     *     summary="Список категорий блога",
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             type="array",
     *             @Items(
     *                 type="object",
     *                 @Property(property="id", type="integer"),
     *                 @Property(property="title", type="string")
     *             )
     *         )
     *     )
     * )
     */
    public function list(Request $request, EntityManagerInterface $entityManager, Connection $connection): array
    {
        $query = $entityManager->createQueryBuilder()->select('c')->from(Category::class, 'c')
            ->where('c.deletedAt is null')
            ->orderBy('c.id', 'desc')
            ->getQuery()
            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker')
            ->setHint(TranslatableListener::HINT_TRANSLATABLE_LOCALE, $request->getLocale())
            ->setHint(TranslatableListener::HINT_FALLBACK, 1);

        $categories = $query->execute();

        return array_map(function (Category $item) {
            $categoryData = new CategoryData();
            $categoryData->id = $item->id();
            $categoryData->title = $item->getTitle();
            $categoryData->slug = $item->getSlug()->value;
            return $categoryData;
        }, $categories);
    }
}
