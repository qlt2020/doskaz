<?php


namespace App\Awards;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/admin/awards")
 * @IsGranted("ROLE_ADMIN")
 */
class AwardsAdminController extends AbstractController
{
    /**
     * @Route(path="/user/{userId}", methods={"GET"})
     * @param Connection $connection
     * @param Request $request
     * @return array|array[]
     */
    public function list(Connection $connection, Request $request)
    {
        $awards = $connection->createQueryBuilder()
            ->addSelect('awards.id')
            ->addSelect('awards.title')
            ->addSelect('awards.type')
            ->addSelect('awards.issued_at as "issuedAt"')
            ->addSelect('users.full_name->>\'firstAndLast\' as "issuedBy"')
            ->from('awards')
            ->leftJoin('awards', 'users', 'users', 'users.id = awards.issued_by')
            ->andWhere('awards.user_id = :userId')
            ->setParameter('userId', $request->get('userId'))
            ->orderBy('issued_at', 'desc')
            ->execute()
            ->fetchAll();

        return array_map(function ($award) use ($connection) {
            return array_replace($award, [
                'issuedAt' => $connection->convertToPHPValue($award['issuedAt'], 'datetimetz_immutable')
            ]);
        }, $awards);
    }

    /**
     * @Route(path="/user/{userId}", methods={"POST"})
     * @param AwardData $data
     * @param Flusher $flusher
     * @param Request $request
     * @param AwardRepository $awardRepository
     */
    public function create(AwardData $data, Flusher $flusher, Request $request, AwardRepository $awardRepository)
    {
        try {
            $award = Award::fromAwardData($data, $request->get('userId'), $this->getUser()->id());
            $awardRepository->add($award);
            $flusher->flush();
            return new JsonResponse(['status' => true, 'data' => $award]);
        }catch (\DomainException $exception)
        {
            return new JsonResponse(['status' => false]);
        }
    }
}
