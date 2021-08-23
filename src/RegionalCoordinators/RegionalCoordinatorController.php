<?php


namespace App\RegionalCoordinators;

use App\Infrastructure\Doctrine\Flusher;
use App\Infrastructure\ObjectResolver\ValidationException;
use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @Route(path="/api/admin/regionalCoordinators")
 * @IsGranted("ROLE_ADMIN")
 */
class RegionalCoordinatorController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array
     */
    public function list(Request $request, Connection $connection)
    {
        $from = "
            SELECT *
            FROM regional_coordinators
            JOIN LATERAL (
                SELECT STRING_AGG(cities.name, ', ' ORDER BY name) as city_names
                FROM cities
                WHERE cities.id IN
                (select JSONB_ARRAY_ELEMENTS_TEXT(regional_coordinators.cities)::INTEGER)
            ) cities ON TRUE
            WHERE deleted_at IS NULL
        ";

        $qb = $connection->createQueryBuilder()
            ->from("($from)", 'regional_coordinators')
            ->leftJoin('regional_coordinators', 'users', 'users', 'users.id = regional_coordinators.user_id');

        $items = (clone $qb)
            ->select([
                'regional_coordinators.id as id',
                'regional_coordinators.city_names as "cityNames"',
                'users.full_name->>\'firstAndLast\' as name'
            ])
            ->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset', 0))
            ->orderBy('regional_coordinators.created_at', 'desc')
            ->execute()
            ->fetchAll();

        return [
            'count' => (clone $qb)->select('count(*)')->execute()->fetchColumn(),
            'items' => $items
        ];
    }

    /**
     * @Route(path="/{id}", methods={"DELETE"})
     * @param RegionalCoordinator $coordinator
     * @param Flusher $flusher
     * @param RegionalCoordinatorRepository $regionalCoordinatorRepository
     */
    public function delete(RegionalCoordinator $coordinator, Flusher $flusher, RegionalCoordinatorRepository $regionalCoordinatorRepository)
    {
        $regionalCoordinatorRepository->remove($coordinator);
        $flusher->flush();
    }

    /**
     * @Route(path="/{id}", methods={"GET"})
     * @param $id
     * @param Connection $connection
     * @return RegionalCoordinatorData
     */
    public function show($id, Connection $connection)
    {
        $coordinator = $connection->createQueryBuilder()
            ->addSelect('id', 'user_id', 'cities')
            ->from('regional_coordinators')
            ->andWhere('id = :id')
            ->andWhere('deleted_at IS NULL')
            ->setParameter('id', $id)
            ->execute()
            ->fetch();

        if (!$coordinator) {
            throw new NotFoundHttpException('Not found');
        }

        return new RegionalCoordinatorData(
            $connection->convertToPHPValue($coordinator['id'], 'uuid'),
            $coordinator['user_id'],
            $connection->convertToPHPValue($coordinator['cities'], CityIdCollection::class)
        );
    }

    /**
     * @Route(path="/{id}", methods={"PUT"})
     * @param RegionalCoordinator $coordinator
     * @param RegionalCoordinatorData $coordinatorData
     * @param Flusher $flusher
     * @throws ValidationException
     */
    public function update(RegionalCoordinator $coordinator, RegionalCoordinatorData $coordinatorData, Flusher $flusher, RegionalCoordinatorRepository $regionalCoordinatorRepository)
    {
        $regionalCoordinator = $regionalCoordinatorRepository->findByUserId($coordinatorData->userId);
        if ($regionalCoordinator !== $coordinator) {
            throw new ValidationException(new ConstraintViolationList([new ConstraintViolation('Этому пользователю уже назначены города', '', [], '', 'userId', '')]));
        }
        $coordinator->updateCities($coordinatorData->cities);
        $flusher->flush();
    }

    /**
     * @Route(path="", methods={"POST"})
     * @param RegionalCoordinatorData $coordinatorData
     * @param Flusher $flusher
     * @param RegionalCoordinatorRepository $regionalCoordinatorRepository
     * @param Connection $connection
     * @return RegionalCoordinatorData
     * @throws ValidationException
     * @throws \Exception
     */
    public function create(RegionalCoordinatorData $coordinatorData, Flusher $flusher, RegionalCoordinatorRepository $regionalCoordinatorRepository, Connection $connection)
    {
        $regionalCoordinator = $regionalCoordinatorRepository->findByUserId($coordinatorData->userId);
        if ($regionalCoordinator) {
            throw new ValidationException(new ConstraintViolationList([new ConstraintViolation('Этому пользователю уже назначены города', '', [], '', 'userId', '')]));
        }
        $id = Uuid::uuid4();
        $regionalCoordinatorRepository->add(new RegionalCoordinator($id, $coordinatorData->userId, $coordinatorData->cities));
        $flusher->flush();
        return $this->show($id, $connection);
    }
}
