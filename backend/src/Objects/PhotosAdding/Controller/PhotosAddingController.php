<?php


namespace App\Objects\PhotosAdding\Controller;

use App\Infrastructure\Doctrine\Flusher;
use App\Objects\MapObject;
use App\Objects\PhotosAdding\Entity\PhotosAddingRequest;
use App\Objects\PhotosAdding\PhotosAddingData;
use App\Objects\PhotosAdding\Repository\PhotosAddingRequestRepository;
use App\Users\Security\AuthenticatedUser;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/objects/{id}", requirements={"id" = "\d+"})
 * @IsGranted("ROLE_USER")
 */
class PhotosAddingController extends AbstractController
{
    /**
     * @Entity("object", expr="repository.findOneBy({""id"": id, ""deletedAt"": null})")
     * @Route(path="/addPhotos", methods={"POST"})
     * @Post(
     *     path="/api/objects/{id}/addPhotos",
     *     summary="Создать запрос на добавление фото к объекту",
     *     tags={"Объекты"},
     *     security={{"clientAuth": {}}},
     *     @Parameter(name="id", in="path", required=true, description="id объекта", @Schema(type="integer"), example=172709),
     *     @RequestBody(
     *         @JsonContent(
     *             @Property(property="photos", type="array", @Items(type="string"), description="Ссылки на фото", example={}),
     *         )
     *     ),
     *     @Response(response=204, description=""),
     *     @Response(response=400, description=""),
     *     @Response(response=401, description=""),
     *     @Response(response=404, description="Объект не найден"),
     *
     * )
     * @param PhotosAddingData $photosAddingData
     * @param MapObject $object
     * @param PhotosAddingRequestRepository $photosAddingRequestRepository
     * @param Flusher $flusher
     */
    public function addPhotos(
        PhotosAddingData $photosAddingData,
        MapObject $object,
        PhotosAddingRequestRepository $photosAddingRequestRepository,
        Flusher $flusher
    ) {
        /**
         * @var $user AuthenticatedUser
         */
        $user = $this->getUser();
        $photosAddingRequest = new PhotosAddingRequest($object->id(), $user->id(), $photosAddingData->photos);
        $photosAddingRequestRepository->add($photosAddingRequest);
        $flusher->flush();
    }
}
