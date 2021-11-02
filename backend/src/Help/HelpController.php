<?php

namespace App\Help;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Schema;

/**
 * @Route(path="/api/help")
 */
class HelpController extends AbstractController
{
    private HelpRepository $repository;

    public function __construct(HelpRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route(methods={"GET"})
     * @Get(
     *     path="/api/help",
     *     tags={"Помощь"},
     *     summary="Список",
     *     @Parameter(name="id", in="query", description="id помощи", @Schema(type="integer")),
     *     @Parameter(name="name", in="query", description="Наименование помощи", @Schema(type="string")),
     *     @Response(response=200, description="")
     * )
     */
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }

    /**
     * @Route(path="/{id}", methods={"GET"}, requirements={"id"="\d+"})
     * @Get(
     *     path="/api/help/{id}",
     *     tags={"Помощь"},
     *     summary="Получение Помощи по Id",
     *     @Response(response=200, description="")
     * )
     */
    public function show($id, Request $request)
    {
        return $this->repository->show($id, $request);
    }
}