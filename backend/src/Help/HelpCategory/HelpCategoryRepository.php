<?php

namespace App\Help\HelpCategory;

use App\Help\Help;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class HelpCategoryRepository
{
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager, Connection $connection)
    {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
    }

    public function index(Request $request)
    {
        $query = $this->connection->createQueryBuilder()
            ->select('help_categories.id as id')
            ->addSelect('help_categories.name_en as name_en')
            ->addSelect('help_categories.name_kz as name_kz')
            ->addSelect('help_categories.name as name')
            ->from('help_categories', 'help_categories')
            ->where('help_categories.deleted_at IS NULL');

        $items = $query->execute()->fetchAll();

        return new JsonResponse(['items' => $items], 200);
    }

    public function show($id)
    {
        return $this->entityManager->getRepository(HelpCategory::class)->find($id)->getAttributes();
    }

    public function store(HelpCategoryData $data)
    {
        $object = new HelpCategory();
        $object->setName($data->name);
        $object->setNameKz($data->name_kz);
        $object->setNameEn($data->name_en);
        $object->setCreatedAt();
        $object->setUpdatedAt();

        return $this->transaction($object);
    }

    public function update(int $id, HelpCategoryData $data)
    {
        $object = $this->entityManager->getRepository(HelpCategory::class)->find($id);

        if (!$object) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Object not found'
            ], 404);
        }

        $object->setName($data->name);
        $object->setNameKz($data->name_kz);
        $object->setNameEn($data->name_en);
        $object->setUpdatedAt();

        return $this->transaction($object);
    }

    public function delete(int $id)
    {
        $object = $this->entityManager->getRepository(HelpCategory::class)->find($id);

        if (!$object) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Object not found'
            ], 404);
        }

        $issetHelp = $this->entityManager->getRepository(Help::class)->findBy(['category' => $object]);
        if ($issetHelp) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Error, isset Help with that Category'
            ], 404);
        }
        $object->setDeletedAt();
        return $this->transaction($object);
    }

    private function transaction(HelpCategory $object)
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
}