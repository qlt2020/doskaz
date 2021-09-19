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

    /**
     * @Route(path = "/statistic",methods={"GET"})
     * @param Request $request
     * @param Connection $connection
     * @return array
     */
    public function statistic(Request $request, Connection $connection): array
    {
        $query = $connection->createQueryBuilder()
            ->select('COUNT(feedback.id) as count')
            ->addSelect('EXTRACT(YEAR FROM feedback.created_at) as year')
            ->addSelect('EXTRACT(MONTH FROM feedback.created_at) as month')
            ->addSelect('cities.name as city_name')
            ->addSelect('cities.id as city_id')
            ->from('feedback', 'feedback')
            ->leftJoin('feedback', 'cities', 'cities', 'cities.id = feedback.city_id');

        if ($request->query->getInt('city_id') != 0){
            $query = $query
                ->andWhere('cities.id = :cityId')
                ->setParameter('cityId', $request->query->getInt('city_id'));
        }

        if ($request->query->getInt('year_id') != 0){
            $query = $query
                ->andWhere('EXTRACT(YEAR FROM feedback.created_at) = :yearId')
                ->setParameter('yearId', $request->query->getInt('year_id'));
        }

        if ($request->query->getAlnum('dateFrom') != ''){
            $dateFrom = date_create($request->query->getAlnum('dateFrom'));
            if ($request->query->getAlnum('dateTo') != ''){
                $dateTo = date_create($request->query->getAlnum('dateTo'));
                $query = $query
                    ->andWhere('feedback.created_at > :dateFrom')
                    ->andWhere('feedback.created_at < :dateTo')
                    ->setParameter('dateFrom', date_format($dateFrom, 'Y-m-d H:i:s'))
                    ->setParameter('dateTo', date_format($dateTo, 'Y-m-d H:i:s'));
            }
            else{
                $query = $query
                    ->andWhere('feedback.created_at > :dateFrom')
                    ->setParameter('dateFrom', date_format($dateFrom, 'Y-m-d H:i:s'));
            }

        }

        $query = $query
            ->groupBy('cities.id, year, month')
            ->execute()
            ->fetchAll();

        if (count($query) == 0)
            return [];

        $years = array();

        foreach($query as $q){
            if ( !in_array($q['year'], $years) )
                array_push($years, $q['year']);
        }

        return ['years' => $years, 'result' => $query];
    }
}
