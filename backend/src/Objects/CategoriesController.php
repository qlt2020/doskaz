<?php


namespace App\Objects;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @Route(path="/api/objectCategories", methods={"GET"})
     * @Route(path="/api/objects/categories", methods={"GET"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return array
     * @Get(
     *     path="/api/objects/categories",
     *     tags={"Объекты"},
     *     summary="Список категорий объектов",
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(ref="#/components/schemas/ObjectCategory")
     *         )
     *     }
     * )
     */
    public function list(Request $request, EntityManagerInterface $entityManager)
    {
        $query = $entityManager->createQueryBuilder()->select('c')->from(Category::class, 'c')
            ->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $request->getLocale()
        );

        $query->setHint(
            TranslatableListener::HINT_FALLBACK,
            1
        );

        /**
         * @var $categories Category[]
         */
        $categories = $query->getResult();

        $map = array_map(function (Category $category) use ($categories) {
            return new CategoryData(
                $category->getId(),
                $category->getTitle(),
                $category->getIcon(),
                array_map(function ($category) {
                    return new CategoryData(
                        $category->getId(),
                        $category->getTitle(),
                        $category->getIcon(),
                        []
                    );
                }, array_values(array_filter($categories, function (Category $subCategory) use ($category) {
                    return $subCategory->getParentId() === $category->getId();
                })))
            );
        }, array_filter($categories, function ($category) {
            return is_null($category->getParentId());
        }));
        $data = [];
        foreach ($map as $item) {
            $data[] = (array)$item;
        }
        return $data;
    }
}
