<?php

namespace App\Help;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route(path="/api/admin/help")
 * @IsGranted("ROLE_ADMIN")
 */
class HelpAdminController extends AbstractController
{
    private HelpRepository $repository;

    public function __construct(HelpRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route(methods={"GET"})
     * @Get(
     *     path="/api/admin/help",
     *     tags={"Помощь"},
     *     summary="Список",
     *     @Parameter(name="id", in="query", description="id помощи", @Schema(type="integer")),
     *     @Parameter(name="name", in="query", description="Наименование помощи", @Schema(type="string")),
     *     @Response(response=200, description="")
     * )
     */
    public function index()
    {
        return $this->repository->indexAdmin();
    }

    /**
     * @Route(path="/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @Get(
     *     path="/api/admin/help/{id}",
     *     tags={"Помощь"},
     *     summary="Получение Помощи по Id",
     *     @Response(response=200, description="")
     * )
     */
    public function show($id)
    {
        return $this->repository->showAdmin($id);
    }

    /**
     * @Route(methods={"POST"})
     * @Post(
     *     path="/api/admin/help",
     *     tags={"Помощь"},
     *     summary="Создание Помощи",
     *     @Response(response=200, description="")
     * )
     */
    public function store(HelpData $data)
    {
        try {
            return  $this->repository->store($data);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @Route(path="/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     * @Put(
     *     path="/api/admin/help/{id}",
     *     tags={"Помощь"},
     *     summary="Изменение Помощи",
     *     @Response(response=200, description="")
     * )
     */
    public function update(int $id, HelpData $data)
    {
        try {
            return $this->repository->update($id, $data);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }

    }

    /**
     * @Route(path="/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @Delete(
     *     path="/api/admin/help/{id}",
     *     tags={"Помощь"},
     *     summary="Удаление Помощи",
     *     @Response(response=200, description="")
     * )
     */
    public function delete(int $id)
    {
        try {
            return $this->repository->delete($id);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @Route(path="publish/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @Get(
     *     path="/api/admin/help/publish/{id}",
     *     tags={"Помощь"},
     *     summary="Публикация/не публикация Помощи",
     *     @Response(response=200, description="")
     * )
     */
    public function publish(int $id)
    {
        try {
            return $this->repository->publish($id);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
}