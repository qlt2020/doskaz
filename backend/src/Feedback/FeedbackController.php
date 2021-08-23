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
     *             @Property(property="email", type="string"),
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
    public function create(FeedbackData $feedbackData, FeedbackRepository $feedbackRepository, Flusher $flusher)
    {
        $feedback = new Feedback($feedbackData->name, $feedbackData->email, $feedbackData->text);
        $feedbackRepository->add($feedback);
        $flusher->flush();
    }
}
