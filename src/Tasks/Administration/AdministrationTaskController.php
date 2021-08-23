<?php


namespace App\Tasks\Administration;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/admin/administrationTasks")
 * @IsGranted("ROLE_ADMIN")
 */
class AdministrationTaskController extends AbstractController
{
    /**
     * @Route(path="", methods={"POST"})
     * @param AdministrationTaskData $data
     * @param Flusher $flusher
     * @param AdministrationTaskRepository $administrationTaskRepository
     */
    public function create(AdministrationTaskData $data, Flusher $flusher, AdministrationTaskRepository $administrationTaskRepository)
    {
        $task = new AdministrationTask($data->name, $data->pointsReward, $data->cityId, $data->categoryId, $data->subCategoryId, $data->area);
        $administrationTaskRepository->add($task);
        $flusher->flush();
        return [
            'id' => $task->id()
        ];
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     * @param string $id
     * @param Connection $connection
     * @return mixed
     */
    public function retrieve(string $id, Connection $connection)
    {
        $data = $connection->createQueryBuilder()
            ->addSelect('id')
            ->addSelect('name')
            ->addSelect('points')
            ->addSelect('city_id')
            ->addSelect('category_id')
            ->addSelect('sub_category_id')
            ->addSelect('closed_at')
            ->addSelect('ST_ASGEOJSON(ST_FlipCoordinates(area))::JSON->\'coordinates\'->0 AS area')
            ->from('administration_tasks')
            ->andWhere('id = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->execute()
            ->fetch();

        if (!$data) {
            throw new NotFoundHttpException("Not Found");
        }

        $taskData = new AdministrationTaskData();
        $taskData->id = $connection->convertToPHPValue($data['id'], 'uuid');
        $taskData->name = $data['name'];
        $taskData->pointsReward = $data['points'];
        $taskData->cityId = $data['city_id'];
        $taskData->categoryId = $data['category_id'];
        $taskData->subCategoryId = $data['sub_category_id'];
        $taskData->area = $connection->convertToPHPValue($data['area'], 'json');
        $taskData->closedAt = $connection->convertToPHPValue($data['closed_at'], 'datetimetz_immutable');
        return $taskData;
    }

    /**
     * @Route(path="/{id}", methods={"PUT"})
     * @param AdministrationTask $administrationTask
     * @param AdministrationTaskData $data
     * @param Flusher $flusher
     */
    public function update(AdministrationTask $administrationTask, AdministrationTaskData $data, Flusher $flusher)
    {
        $administrationTask->update($data->name, $data->pointsReward, $data->cityId, $data->categoryId, $data->subCategoryId, $data->area);
        $flusher->flush();
    }

    /**
     * @Route(path="/{id}/close", methods={"POST"})
     * @Route(path="/{id}", methods={"DELETE"})
     * @param AdministrationTask $administrationTask
     * @param Flusher $flusher
     */
    public function close(AdministrationTask $administrationTask, Flusher $flusher)
    {
        $administrationTask->close();
        $flusher->flush();
    }

    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array
     */
    public function list(Request $request, Connection $connection)
    {
        $qb = $connection->createQueryBuilder()
            ->from('administration_tasks')
            ->andWhere('closed_at IS NULL');

        $items = (clone $qb)
            ->addSelect('administration_tasks.id')
            ->addSelect('administration_tasks.name as "taskName"')
            ->addSelect('cities.name as "cityName"')
            ->addSelect('administration_tasks.closed_at as "closedAt"')
            ->addSelect('administration_tasks.points')
            ->leftJoin('administration_tasks', 'cities', 'cities', 'cities.id = administration_tasks.city_id')
            ->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset', 0))
            ->orderBy('administration_tasks.created_at', 'desc')
            ->execute()
            ->fetchAll();

        return [
            'count' => (clone $qb)->select('COUNT(*)')->execute()->fetchColumn(),
            'items' => $items
        ];
    }
}
