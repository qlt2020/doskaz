<?php


namespace App\Blog\Categories;

use App\Blog\Meta;
use App\Blog\MetaData;
use App\Blog\SlugFactory;
use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/admin/blog/categories")
 */
class CategoriesAdminController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(methods={"POST"})
     * @param Connection $connection
     * @param CategoryData $categoryData
     * @param CategoryRepository $categoryRepository
     * @param SlugFactory $slugFactory
     * @param Flusher $flusher
     * @return CategoryData
     */
    public function create(Connection $connection, CategoryData $categoryData, CategoryRepository $categoryRepository, SlugFactory $slugFactory, Flusher $flusher)
    {
        $category = new Category($categoryData->title, $slugFactory->make($categoryData->slug ?: $categoryData->title), Meta::fromMetaData($categoryData->meta));
        $categoryRepository->add($category);
        $flusher->flush();
        return $this->retrieve($category->id(), $connection);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/{id}", methods={"PUT"})
     * @param Category $category
     * @param CategoryData $categoryData
     * @param SlugFactory $slugFactory
     * @param Flusher $flusher
     * @return void
     */
    public function update(Category $category, CategoryData $categoryData, SlugFactory $slugFactory, Flusher $flusher)
    {
        $category->update($categoryData->title, $slugFactory->make($categoryData->slug ?: $categoryData->title), Meta::fromMetaData($categoryData->meta));
        $flusher->flush();
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     * @param $id
     * @param Connection $connection
     * @return CategoryData
     */
    public function retrieve($id, Connection $connection): CategoryData
    {
        $data = $connection->createQueryBuilder()
            ->addSelect('id')
            ->addSelect('title')
            ->addSelect('slug_value as slug')
            ->addSelect('meta')
            ->from('blog_categories')
            ->andWhere('id = :id')
            ->andWhere('deleted_at IS NULL')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        if (!$data) {
            throw new NotFoundHttpException();
        }

        $categoryData = new CategoryData();
        $categoryData->id = $data['id'];
        $categoryData->title = $data['title'];
        $categoryData->slug = $data['slug'];
        $categoryData->meta = MetaData::fromMeta($connection->convertToPHPValue($data['meta'], Meta::class));
        return $categoryData;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/{id}", methods={"DELETE"})
     * @param Category $category
     * @param Flusher $flusher
     */
    public function delete(Category $category, Flusher $flusher)
    {
        $category->markAsDeleted();
        $flusher->flush();
    }

    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return CategoryData[]
     */
    public function list(Request $request, Connection $connection): array
    {
        $qb = $connection->createQueryBuilder()->from('blog_categories')
            ->andWhere('deleted_at IS NULL');

        $items = (clone $qb)
            ->addSelect('id')
            ->addSelect('title')
            ->addSelect('slug_value as slug')
            ->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset', 0))
            ->orderBy('id', 'desc')
            ->execute()
            ->fetchAll();

        return [
            'items' => array_map(function ($item) {
                $categoryData = new CategoryData();
                $categoryData->id = $item['id'];
                $categoryData->title = $item['title'];
                $categoryData->slug = $item['slug'];
                return $categoryData;
            }, $items),
            'count' => $qb->select('count(*)')->execute()->fetchColumn()
        ];
    }
}
