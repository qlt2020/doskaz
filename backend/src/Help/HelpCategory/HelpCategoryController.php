<?php

namespace App\Help\HelpCategory;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
 * @Route(path="/api/help-category")
 */
class HelpCategoryController extends AbstractController
{
    private $repository;

    public function __construct(HelpCategoryRepository $repository){
        $this->repository = $repository;
    }
    /**
     * @Route(methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/help-category",
     *     tags={"Категории раздела Помощь"},
     *     summary="Список категорий",
     *     @Parameter(name="id", in="query", description="id категории", @Schema(type="integer")),
     *     @Parameter(name="name", in="query", description="Наименование категории", @Schema(type="string")),
     *     @Response(response=200, description="")
     * )
     */
    public function index(Request $request){
        return $this->repository->index($request);
    }

    /**
     * @Route(methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     * @Post(
     *     path="/api/help-category",
     *     tags={"Категории раздела Помощь"},
     *     summary="Создание категории раздела Помощь",
     *     @Response(response=200, description="")
     * )
     */
    public function store(HelpCategoryData $categoryData)
    {
        return $this->repository->store($categoryData);
    }

    /**
     * @Route(path="/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     * @Get(
     *     path="/api/help-category/{id}",
     *     tags={"Категории раздела Помощь"},
     *     summary="Получение категории раздела Помощь по Id",
     *     @Response(response=200, description="")
     * )
     */
    public function show($id)
    {
        return $this->repository->show($id);
    }

    /**
     * @Route(path="/{id}", methods={"PUT"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     * @Put(
     *     path="/api/help-category/{id}",
     *     tags={"Категории раздела Помощь"},
     *     summary="Изменение категории раздела Помощь",
     *     @Response(response=200, description="")
     * )
     */
    public function update(int $id, HelpCategoryData $categoryData){
        return $this->repository->update($id, $categoryData);
    }

    /**
     * @Route(path="/{id}", methods={"DELETE"}, requirements={"id"="\d+"})
     * @IsGranted("ROLE_ADMIN")
     * @Delete(
     *     path="/api/help-category/{id}",
     *     tags={"Категории раздела Помощь"},
     *     summary="Удаление категории раздела Помощь",
     *     @Response(response=200, description="")
     * )
     */
    public function delete(int $id){
        return $this->repository->delete($id);
    }
}