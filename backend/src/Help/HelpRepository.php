<?php

namespace App\Help;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HelpRepository
{
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager, Connection $connection)
    {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
    }

    public function index(Request $request, array $roles = null)
    {
        $locale = $request->getLocale();
        $locale = $locale === 'ru' ? '' : '_' . $locale;
        $query = $this->query(null, $request, $roles);
        $items = $query->execute()->fetchAll();

        return new JsonResponse(['items' => $items], 200);
    }

    public function show($id, Request $request, array $roles = null){
        $item = $this->query($id, $request, $roles)->execute()->fetchAll();
        if (count($item) == 0) {
            return new JsonResponse('Not found', 404);
        }
        return new JsonResponse($item[0], 200);
    }

    public function store(HelpData $data)
    {
        $object = new Help();

        $object->setTitle($data->title);
        $object->setTitleKz($data->title_kz);
        $object->setTitleEn($data->title_en);
        $object->setDescription($data->description);
        $object->setDescriptionKz($data->description_kz);
        $object->setDescriptionEn($data->description_en);
        $object->setCategory($this->entityManager, $data->category);
        $object->setImage($data->image);
        $object->setCreatedAt();
        $object->setUpdatedAt();

        return $this->transaction($object, 'Successfully created');
    }

    public function update(int $id, HelpData $data)
    {
        $object = $this->entityManager->getRepository(Help::class)->find($id);

        if (!$object) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Object not found'
            ], 404);
        }

        $object->setTitle($data->title);
        $object->setTitleKz($data->title_kz);
        $object->setTitleEn($data->title_en);
        $object->setDescription($data->description);
        $object->setDescriptionKz($data->description_kz);
        $object->setDescriptionEn($data->description_en);
        $object->setCategory($this->entityManager, $data->category);
        $object->setImage($data->image);

        $object->setUpdatedAt();

        return $this->transaction($object, 'Successfully updated');
    }

    public function delete(int $id)
    {
        $object = $this->entityManager->getRepository(Help::class)->find($id);

        if (!$object) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Object not found'
            ], 404);
        }

        $object->setDeletedAt();

        return $this->transaction($object, 'Successfully deleted');
    }

    public function publish(int $id)
    {
        $object = $this->entityManager->getRepository(Help::class)->find($id);

        if (!$object) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Object not found'
            ], 404);
        }

        $object->setPublish();

        return $this->transaction($object, 'Successfully changed status');
    }

    private function transaction(Help $object, string $message = '')
    {
        $this->entityManager->beginTransaction();
        try {
            $this->entityManager->persist($object);
            $this->entityManager->flush();
            $this->entityManager->commit();

            return $object->getAttributes();
        } catch (\Exception $exception) {
            $this->entityManager->rollback();
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    private function query(?int $id, Request $request, array $roles = null){
        $locale = $request->getLocale();
        $locale = $locale === 'ru' ? '' : '_' . $locale;
        $query = $this->connection->createQueryBuilder()
            ->select('helps.id as id')
            ->addSelect('helps.title' . $locale . ' as title')
            ->addSelect('helps.description' . $locale . ' as description')
            ->addSelect('helps.image as image')
            ->addSelect('help_categories.id as category_id')
            ->addSelect('help_categories.name' . $locale . ' as category_name')
            ->from('helps', 'helps')
            ->join('helps', 'help_categories', 'help_categories', 'helps.category_id = help_categories.id')
            ->where('helps.deleted_at IS NULL');

        if ($roles != null && in_array('ROLE_ADMIN', $roles)) {
            $query = $query
                ->orWhere('helps.is_published = false and helps.deleted_at IS NULL')
                ->orWhere('helps.is_published = true and helps.deleted_at IS NULL');
        } else {
            $query = $query
                ->andWhere('helps.is_published = true and helps.deleted_at IS NULL');
        }

        if (isset($id)) {
            $query = $query
                ->andWhere( 'helps.id = :id')
                ->setParameter('id', $id);
        }

        return $query;
    }
}