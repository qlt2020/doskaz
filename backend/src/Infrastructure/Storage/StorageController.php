<?php
declare(strict_types=1);

namespace App\Infrastructure\Storage;

use App\Infrastructure\ImageConversion\ImageConversion;
use Hoa\Mime\Mime;
use League\Flysystem\FilesystemInterface;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Blog\Image;

/**
 * @Route(path="/api/storage")
 */
final class StorageController extends AbstractController
{
    private FilesystemInterface $filesystem;

    private MessageBusInterface $messageBus;

    public function __construct(FilesystemInterface $defaultStorage, MessageBusInterface $messageBus)
    {
        $this->filesystem = $defaultStorage;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route(path="/upload", methods={"POST"})
     * @param Request $request
     * @return array
     * @throws \Exception
     * @Post(
     *     path="/api/storage/upload",
     *     summary="Загрузка файла",
     *     tags={"Storage"},
     *     security={{"clientAuth": {}}},
     *     @RequestBody(
     *         description="файл для загрузки",
     *         @MediaType(
     *             mediaType="application/octet-stream"
     *         )
     *     ),
     *     @Response(
     *         response=200,
     *         description="",
     *         @JsonContent(
     *             @Property(property="path", type="string")
     *         )
     *     )
     * )
     */
    public function upload(Request $request)
    {
        $name = bin2hex(random_bytes(16));
        $this->filesystem->assertAbsent($name);
        $this->filesystem->writeStream($name, $request->getContent(true));
        $mime = $this->filesystem->getMimetype($name);
        $extensions = Mime::getExtensionsFromMime($mime);
        $nameWithExtension = $name.'.'.$extensions[0];
        $this->filesystem->rename($name, $nameWithExtension);
        $this->messageBus->dispatch(new ImageConversion($nameWithExtension));

        return [
            'path' => "/storage/{$nameWithExtension}"
        ];
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route(path="/preview/{imagePath}", requirements={"imagePath" = ".+"})
     * @param $imagePath
     * @param Request $request
     * @return RedirectResponse
     */
    public function preview($imagePath, Request $request)
    {
        $img = new Image();
        $img->image = $imagePath;

        if ($request->query->has('x') || $request->query->has('y')) {
            $img->cropData = [
                'x' => $request->query->getInt('x'),
                'y' => $request->query->getInt('y'),
                'width' => $request->query->getInt('width'),
                'height' => $request->query->getInt('height'),
            ];
        }
        return $this->redirect($img->resize(200, 0));
    }
}
