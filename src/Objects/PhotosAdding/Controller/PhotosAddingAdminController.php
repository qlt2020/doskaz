<?php


namespace App\Objects\PhotosAdding\Controller;

use App\AdminpanelPermissions\AdminpanelPermission;
use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\FileReferenceCollection;
use App\Objects\PhotosAdding\Entity\PhotosAddingRequest;
use App\Objects\PhotosAdding\PhotosAddingData;
use App\RegionalCoordinators\RegionalCoordinatorCitiesFinder;
use App\RegionalCoordinators\RegionalCoordinatorRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/admin/photosAdding")
 * @IsGranted("objects_access")
 */
class PhotosAddingAdminController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @param RegionalCoordinatorCitiesFinder $coordinatorCitiesFinder
     * @param RegionalCoordinatorRepository $regionalCoordinatorRepository
     * @return array
     * @throws Exception
     */
    public function list(Request $request, Connection $connection, RegionalCoordinatorCitiesFinder $coordinatorCitiesFinder, RegionalCoordinatorRepository $regionalCoordinatorRepository): array
    {
        $query = $connection->createQueryBuilder()
            ->from('object_photos_adding_requests', 'r')
            ->andWhere("r.status = 'on_review'")
            ->join('r', 'objects', 'objects', 'objects.id = r.object_id');

        if ($regionalCoordinatorRepository->findByUserId($this->getUser()->id())) {
            $cities = $coordinatorCitiesFinder->find($this->getUser()->id());
            $query->andWhere('
                EXISTS(
                    SELECT 1
                    FROM cities_geometry
                    WHERE cities_geometry.id IN (:cities)
                    AND ST_Contains(cities_geometry.geometry, objects.point_value::geometry)
                    LIMIT 1
                )
            ')->setParameter('cities', $cities, Connection::PARAM_INT_ARRAY);
        }

        $items = (clone $query)
            ->addSelect('r.id')
            ->addSelect('r.object_id as "objectId"')
            ->addSelect('o.title as object')
            ->addSelect('r.created_at as "createdAt"')
            ->leftJoin('r', 'objects', 'o', 'o.id = r.object_id')
            ->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset', 0))
            ->addOrderBy('r.id', 'desc')
            ->execute()->fetchAll();

        return [
            'items' => array_map(fn($item) => array_replace($item, [
                'createdAt' => $connection->convertToPHPValue($item['createdAt'], 'datetimetz_immutable')
            ]), $items),
            'count' => $query->select('COUNT(*)')->execute()->fetchColumn()
        ];
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     * @param $id
     * @param Connection $connection
     * @return mixed
     * @throws Exception
     */
    public function retrieve($id, Connection $connection)
    {
        $item = $connection->createQueryBuilder()
            ->addSelect('id')
            ->addSelect('status')
            ->addSelect('photos')
            ->from('object_photos_adding_requests')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$item) {
            throw new NotFoundHttpException();
        }

        return array_replace($item, [
            'photos' => $connection->convertToPHPValue($item['photos'], FileReferenceCollection::class)
        ]);
    }

    /**
     * @Route(path="/{item}", methods={"PUT"})
     * @param PhotosAddingRequest $item
     * @param PhotosAddingData $photosAddingData
     * @param EntityManagerInterface $entityManager
     */
    public function update(PhotosAddingRequest $item, PhotosAddingData $photosAddingData, EntityManagerInterface $entityManager)
    {
        $entityManager->transactional(function () use ($item, $photosAddingData) {
            $item->updatePhotos($photosAddingData->photos);
        });
    }

    /**
     * @Route(path="/{item}/approve", methods={"POST"})
     * @param PhotosAddingRequest $item
     * @param EntityManagerInterface $entityManager
     */
    public function approve(PhotosAddingRequest $item, EntityManagerInterface $entityManager)
    {
        $entityManager->transactional(function () use ($item) {
            $item->approve($this->getUser()->id());
        });
    }

    /**
     * @Route(path="/{item}", methods={"DELETE"})
     * @param PhotosAddingRequest $item
     * @param Flusher $flusher
     */
    public function delete(PhotosAddingRequest $item, Flusher $flusher) {
        $item->markAsDeleted();
        $flusher->flush();
    }
}
