<?php


namespace App\Objects\Adding;

use App\AdminpanelPermissions\AdminpanelPermission;
use App\Infrastructure\Doctrine\Flusher;
use App\Objects\MapObjectRepository;
use App\RegionalCoordinators\RegionalCoordinatorCitiesFinder;
use App\RegionalCoordinators\RegionalCoordinatorRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/admin/addingRequests")
 * @IsGranted("ROLE_USER")
 */
class AdminController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Connection $connection
     * @param Request $request
     * @param RegionalCoordinatorRepository $regionalCoordinatorRepository
     * @param RegionalCoordinatorCitiesFinder $coordinatorCitiesFinder
     * @return array
     */
    public function list(Connection $connection, Request $request, RegionalCoordinatorRepository $regionalCoordinatorRepository, RegionalCoordinatorCitiesFinder $coordinatorCitiesFinder)
    {
        //  $this->denyAccessUnlessGranted(AdminpanelPermission::ADDING_REQUESTS_ACCESS);

        $requestsQuery = $connection->createQueryBuilder()
            ->select([
                'adding_requests.id',
                "data->>'form' as form",
                "data->'first'->>'name' as name",
                "data->'first'->>'address' as address",
                'object_categories.title as category',
                'adding_requests.created_at as "createdAt"',
                'cities.name as city',
            ])
            ->from('adding_requests')
            ->leftJoin('adding_requests', 'object_categories', 'object_categories', '(adding_requests.data->\'first\'->>\'categoryId\')::int = object_categories.id')
            ->leftJoin('adding_requests', 'cities_geometry', 'cities_geometry', 'ST_Contains(cities_geometry.geometry, ST_SetSRID(ST_MakePoint((data->\'first\'->\'point\'->>1)::float, (data->\'first\'->\'point\'->>0)::float), 4326))')
            ->leftJoin('cities_geometry', 'cities', 'cities', 'cities.id = cities_geometry.id')
            ->andWhere('adding_requests.deleted_at is null')
            ->andWhere('adding_requests.approved_at is null');


        if ($regionalCoordinatorRepository->findByUserId($this->getUser()->id())) {
            $cities = $coordinatorCitiesFinder->find($this->getUser()->id());
            $requestsQuery->andWhere('
                EXISTS(
                    SELECT 1
                    FROM cities_geometry
                    WHERE cities_geometry.id IN (:cities)
                    AND ST_Contains(cities_geometry.geometry, ST_SetSRID(ST_MakePoint((data->\'first\'->\'point\'->>1)::float, (data->\'first\'->\'point\'->>0)::float), 4326))
                    LIMIT 1
                )
            ')->setParameter('cities', $cities, Connection::PARAM_INT_ARRAY);
        }

        $requestsData = (clone $requestsQuery)
            ->setMaxResults(20)
            ->setFirstResult($request->query->getInt('offset', 0))
            ->orderBy('id', 'desc')
            ->execute()->fetchAll();

        return [
            'count' => (clone $requestsQuery)->select('count(*)')->execute()->fetchColumn(),
            'items' => array_map(function ($request) use ($connection) {
                return array_replace($request, [
                    'createdAt' => $connection->convertToPHPValue($request['createdAt'], 'datetimetz_immutable')
                ]);
            }, $requestsData)
        ];
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     * @param $id
     * @param Connection $connection
     * @return AddingRequestReviewData
     */
    public function show($id, Connection $connection)
    {
        $this->denyAccessUnlessGranted(AdminpanelPermission::ADDING_REQUESTS_ACCESS);

        $item = $connection->createQueryBuilder()
            ->select(['id', 'data', 'approved_at'])
            ->from('adding_requests')
            ->andWhere('id = :id')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$item) {
            throw new NotFoundHttpException();
        }

        return new AddingRequestReviewData(
            $item['id'],
            $connection->convertToPHPValue($item['data'], Form::class),
            $connection->convertToPHPValue($item['approved_at'], 'datetimetz_immutable')
        );
    }

    /**
     * @Route(path="/{id}", methods={"PUT"})
     * @param AddingRequest $addingRequest
     * @param AddingRequestReviewData $addingRequestReviewData
     * @param Flusher $flusher
     * @return JsonResponse|void
     */
    public function update(AddingRequest $addingRequest, AddingRequestReviewData $addingRequestReviewData, Flusher $flusher)
    {
        try {
            $this->denyAccessUnlessGranted(AdminpanelPermission::ADDING_REQUESTS_ACCESS);
            $addingRequest->updateData($addingRequestReviewData->form);
            $flusher->flush();
            return new JsonResponse([
                'status' => true,
                'message' => 'Успешно'
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ],400);
        }
    }

    /**
     * @Route(path="/{id}/approve", methods={"POST"})
     * @param AddingRequest $request
     * @param MapObjectRepository $mapObjectRepository
     * @param Flusher $flusher
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function approve(AddingRequest $request, MapObjectRepository $mapObjectRepository, Flusher $flusher, EntityManagerInterface $em)
    {
        $em->getConnection()->beginTransaction();
        try {
            $this->denyAccessUnlessGranted(AdminpanelPermission::ADDING_REQUESTS_ACCESS);
            $mapObject = $request->approve();
            $mapObjectRepository->add($mapObject);
            $flusher->flush();
            $em->getConnection()->commit();
            return new JsonResponse([
                'status' => true,
                'message' => "Успешно"
            ]);
        } catch (\Exception $exception) {
            $em->getConnection()->rollBack();
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ],400);
        }
    }

    /**
     * @Route(path="/{id}", methods={"DELETE"})
     * @param AddingRequest $request
     * @param Flusher $flusher
     */
    public function delete(AddingRequest $request, Flusher $flusher)
    {
        try {
            $this->denyAccessUnlessGranted(AdminpanelPermission::ADDING_REQUESTS_ACCESS);
            $request->markAsDeleted();
            $flusher->flush();
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ],400);
        }
    }
}
