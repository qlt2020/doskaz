<?php


namespace App\Feedback;

use App\Infrastructure\Doctrine\Flusher;
use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/admin/feedback")
 * @IsGranted("ROLE_ADMIN")
 */
class FeedbackAdminController extends AbstractController
{
    /**
     * @Route(methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array
     */
    public function list(Request $request, Connection $connection)
    {
        $query = $connection->createQueryBuilder()
            ->from('feedback')
            ->where('deleted_at IS NULL');

        $items = (clone $query)
            ->select('*')
            ->orderBy('id', 'desc')
            ->setMaxResults($request->query->getInt('limit', 20))
            ->setFirstResult($request->query->getInt('offset', 0))
            ->execute()
            ->fetchAll();

        return [
            'items' => array_map(function ($item) use ($connection) {
                return array_replace($item, [
                    'created_at' => $connection->convertToPHPValue($item['created_at'], 'datetimetz_immutable')
                ]);
            }, $items),
            'count' => $query->select('COUNT(*)')->execute()->fetchColumn()
        ];
    }

    /**
     * @Route(path="/{id}", methods={"DELETE"})
     * @param Feedback $feedback
     * @param Flusher $flusher
     */
    public function delete(Feedback $feedback, Flusher $flusher)
    {
        $feedback->delete();
        $flusher->flush();
    }
}
