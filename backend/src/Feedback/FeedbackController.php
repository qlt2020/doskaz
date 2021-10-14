<?php


namespace App\Feedback;

use App\Infrastructure\Doctrine\Flusher;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

/**
 * @Route(path="/api/feedback")
 */
class FeedbackController extends AbstractController
{
    /**
     * @Route(methods={"POST"})
     * @param FeedbackData $feedbackData
     * @param FeedbackRepository $feedbackRepository
     * @param Flusher $flusher
     * @Post(
     *     path="/api/feedback",
     *     tags={"Обратная связь"},
     *     summary="Отправка обратной связи",
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="name", type="string"),
     *             @Property(property="surname", type="string"),
     *             @Property(property="email", type="string"),
     *             @Property(property="city_id", type="integer"),
     *             @Property(property="text", type="string"),
     *         )
     *     ),
     *     @Response(response="204", description=""),
     *     @Response(
     *         response=400,
     *         description="Validation Failed",
     *         @JsonContent(
     *             @Property(property="message", type="string", example="Validation Failed"),
     *             @Property(property="code", type="number", example=400),
     *             @Property(
     *                 property="errors",
     *                 type="array",
     *                 @Items(
     *                     type="object",
     *                     @Property(property="property", type="string", description="Имя свойства"),
     *                     @Property(property="message", type="string", description="Текст ошибки"),
     *                 )
     *             )
     *         )
     *     ),
     * )
     */
    public function create(FeedbackData $feedbackData, FeedbackRepository $feedbackRepository, Flusher $flusher, MailerInterface $mailer)
    {
        $em = $this->getDoctrine()->getManager();
        $city = $feedbackData->city_id ? $em->getRepository('App\Cities\Cities')->find($feedbackData->city_id) : null;

        $city_name = $city ? $city->getName() : '';

        $feedback = new Feedback($feedbackData->name, $feedbackData->surname, $feedbackData->email, $feedbackData->text, $city);
        $feedbackRepository->add($feedback);
        $flusher->flush();

        $email_from = $this->getParameter('app.email_from');
        $email_to = $this->getParameter('app.email_to');

        if($email_from && $email_to) {
            $email = (new Email())
            ->from($email_from)
            ->to($email_to)
            ->subject('Обратная связь DOSKAZ')
            ->text('Обратная связь DOSKAZ')
            ->html('<p>Имя:' . $feedbackData->name . '</p>' .
                   '<p>Фамилия:' . $feedbackData->surname . '</p>' .
                   '<p>Почта:' . $feedbackData->email . '</p>' .
                   '<p>Город:' . $city_name . '</p>' .
                   '<p>Текст:' . $feedbackData->text . '</p>');

            try{
                $mailer->send($email);
            }
            catch(\Exception $e){
                error_log($e->getMessage());
            }
        }
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
