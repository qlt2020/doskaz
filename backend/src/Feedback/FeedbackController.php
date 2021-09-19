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
}
