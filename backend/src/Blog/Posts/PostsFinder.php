<?php
declare(strict_types=1);

namespace App\Blog\Posts;

use App\Blog\Image;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;


final class PostsFinder
{
    const ITEMS_PER_PAGE = 10;

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function initializeQuery(?string $lang): QueryBuilder
    {
        $lang = $lang === 'ru' ? '' : '_' . $lang;

        $query = $this->connection->createQueryBuilder()
            ->addSelect('blog_posts.id')
            ->addSelect('blog_posts.title' . $lang . ' as title')
            ->addSelect('blog_posts.annotation' . $lang . ' as annotation')
            ->addSelect('blog_posts.content' . $lang . ' as content')
            ->addSelect('blog_posts.slug_value as slug')
            ->addSelect('blog_categories.slug_value as "categorySlug"')
            ->addSelect('blog_categories.id as "categoryId"')
            ->addSelect('blog_posts.image')
            ->addSelect('blog_categories.title' . $lang . ' as category_title')
            ->addSelect('blog_posts.published_at')
            ->addSelect('blog_posts.meta_title')
            ->addSelect('blog_posts.meta_description')
            ->addSelect("coalesce(blog_posts.meta_keywords, blog_categories.meta->>'keywords') as meta_keywords")
            ->addSelect('blog_posts.meta_og_title')
            ->addSelect('blog_posts.meta_og_description')
            ->addSelect("coalesce(blog_posts.meta_og_image, image) as meta_og_image")
            ->from('blog_posts')
            ->andWhere('blog_posts.deleted_at IS NULL AND blog_posts.is_published = true')
            ->andWhere('blog_posts.published_at <= CURRENT_TIMESTAMP')
            ->join('blog_posts', 'blog_categories', 'blog_categories', 'blog_posts.category_id = blog_categories.id');

        return $query;
    }

    private function mapItem(array $data)
    {
        $image = $this->connection->convertToPHPValue($data['image'], Image::class);
        $ogImage = $this->connection->convertToPHPValue($data['meta_og_image'], Image::class);

        $annotation = strip_tags($data['annotation'] ?? '');

        return [
            'id' => $data['id'],
            'title' => $data['title'],
            'annotation' => $annotation,
            'content' => $data['content'],
            'slug' => $data['slug'],
            'categorySlug' => $data['categorySlug'],
            'categoryId' => $data['categoryId'],
            'categoryTitle' => $data['category_title'],
            'publishedAt' => $this->connection->convertToPHPValue($data['published_at'], 'datetimetz_immutable'),
            'image' => $image ? $image->resize(710) : null,
            'previewImage' => $image ? $image->resize(260) : null,
            'meta' => [
                'title' => $data['meta_title'] ?: $data['title'],
                'description' => $data['meta_description'] ?: $annotation,
                'keywords' => $data['meta_keywords'],
                'ogTitle' => $data['meta_og_title'] ?: $data['title'],
                'ogDescription' => $data['meta_og_description'] ?: $annotation,
                'ogImage' => $ogImage ? $ogImage->resize(710) : null
            ]
        ];
    }

    public function find(array $filter = [], int $page = 1, int $perPage = self::ITEMS_PER_PAGE, ?string $lang = null): array
    {
        $queryBuilder = $this->initializeQuery($lang);

        if (isset($filter['post_date_from']) && isset($filter['post_date_to']) && !is_null($filter['post_date_from']) && !is_null($filter['post_date_to'])) {
            $post_date_from = $filter['post_date_from'];
            $post_date_to = new \DateTime($filter['post_date_to']);
            $post_date_to->add(new \DateInterval('P1D'));
            $post_date_to = $post_date_to->format('Y-m-d');

            $queryBuilder->where('blog_posts.published_at >= :post_date_from')
                            ->andWhere('blog_posts.published_at <= :post_date_to')
                            ->setParameter('post_date_from', $post_date_from)
                            ->setParameter('post_date_to', $post_date_to);
        }

        foreach ($filter as $property => $filterValue) {
            switch ($property) {
                case 'category':
                    $queryBuilder->andWhere('blog_categories.slug_value = :categorySlug')
                        ->setParameter('categorySlug', $filterValue);
                    break;
                case 'categoryId':
                    $queryBuilder->andWhere('blog_posts.category_id = :categoryId')
                        ->setParameter('categoryId', $filterValue);
                    break;
                case 'period':
                    switch ($filterValue) {
                        case 'year':
                            $queryBuilder->andWhere("blog_posts.published_at > CURRENT_TIMESTAMP - INTERVAL '1 YEAR'");
                            break;
                        case 'month':
                            $queryBuilder->andWhere("blog_posts.published_at > CURRENT_TIMESTAMP - INTERVAL '1 MONTH'");
                            break;
                        case 'week':
                            $queryBuilder->andWhere("blog_posts.published_at > CURRENT_TIMESTAMP - INTERVAL '1 WEEK'");
                            break;
                    }
                    break;
                case 'search':
                    if ($filterValue) {
                        $queryBuilder->andWhere("to_tsvector('russian', concat(blog_posts.title, blog_posts.annotation, blog_posts.content)) @@ websearch_to_tsquery('russian', :search)")->setParameter('search', $filterValue);
                    }
                    break;
            }
        }


        $count = (clone $queryBuilder)->select('count(*)')->execute()->fetchColumn();

        $items = array_map(function ($item) {
            return $this->mapItem($item);
        }, $queryBuilder->setMaxResults($perPage)
            ->orderBy('published_at', 'desc')
            ->setFirstResult(($page - 1) * $perPage)->execute()->fetchAll());

        return [
            'items' => $items,
            'pages' => ceil($count / $perPage)
        ];
    }

    public function findOneBySlug(string $slug, string $lang): ?array
    {
        $item = $this->initializeQuery($lang)
            ->andWhere('blog_posts.slug_value = :slug')
            ->setParameter('slug', $slug)
            ->execute()->fetch();

        if (!$item) {
            return null;
        }

        $similarPosts = $this->initializeQuery($lang)
            ->andWhere('blog_posts.id != :id')
            ->orderBy('similarity(:title, blog_posts.title)', 'desc')
            ->setParameter('title', $item['title'])
            ->setParameter('id', $item['id'])
            ->setMaxResults(3)
            ->execute()->fetchAll();

        return [
            'post' => $this->mapItem($item),
            'similar' => array_map(function ($item) {
                $post = $this->mapItem($item);
                return [
                    'title' => $post['title'],
                    'image' => $post['previewImage'],
                    'slug' => $post['slug'],
                    'categorySlug' => $post['categorySlug']
                ];
            }, $similarPosts)
        ];
    }

    public function findOneById($id, string $lang): ?array
    {
        $item = $this->initializeQuery($lang)
            ->andWhere('blog_posts.id = :id')
            ->setParameter('id', $id)
            ->execute()->fetch();

        if (!$item) {
            return null;
        }

        $similarPosts = $this->initializeQuery($lang)
            ->andWhere('blog_posts.id != :id')
            ->orderBy('similarity(:title, blog_posts.title)', 'desc')
            ->setParameter('title', $item['title'])
            ->setParameter('id', $item['id'])
            ->setMaxResults(3)
            ->execute()->fetchAll();

        return [
            'post' => $this->mapItem($item),
            'similar' => array_map(function ($item) {
                $post = $this->mapItem($item);
                return [
                    'id' => $post['id'],
                    'categoryId' => $item['categoryId'],
                    'title' => $post['title'],
                    'image' => $post['previewImage'],
                    'slug' => $post['slug'],
                    'categorySlug' => $post['categorySlug']
                ];
            }, $similarPosts)
        ];
    }
}
