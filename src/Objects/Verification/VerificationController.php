<?php


namespace App\Objects\Verification;

use App\Infrastructure\Doctrine\Flusher;
use App\Objects\MapObject;
use App\Objects\MapObjectRepository;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class VerificationController extends AbstractController
{
    /**
     * @Route(path="/api/objects/{id}/verification/{status}", requirements={"id" = "\d+"}, methods={"POST"})
     * @param $status
     * @param $id
     * @param Flusher $flusher
     * @param VerificationRepository $verificationRepository
     * @param MapObjectRepository $mapObjectRepository
     * @Post(
     *     path="/api/objects/{id}/verification/{status}",
     *     tags={"Объекты"},
     *     summary="Верификация объекта",
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="id", in="path", required=true, description="id объекта", @Schema(type="integer"), example=172709),
     *     @Parameter(name="status", in="path", required=true, description="", @Schema(type="string", enum={"confirm", "reject"})),
     *     @Response(response=404, description=""),
     *     @Response(response=204, description=""),
     *     @Response(response=401, description=""),
     * )
     */
    public function verify($status, $id, Flusher $flusher, VerificationRepository $verificationRepository, MapObjectRepository $mapObjectRepository)
    {
        $mapObject = $mapObjectRepository->find($id);
        if (!$mapObject) {
            throw new NotFoundHttpException('Not found');
        }

        $verification = $verificationRepository->find($mapObject->uuid());
        switch ($status) {
            case 'confirm':
                $verification->confirm($this->getUser()->id());
                break;
            case 'reject':
                $verification->reject($this->getUser()->id());
                break;
        }

        $flusher->flush();
    }
}
