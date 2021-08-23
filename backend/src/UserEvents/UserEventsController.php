<?php


namespace App\UserEvents;

use OpenApi\Annotations\Get;
use OpenApi\Annotations\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserEventsController extends AbstractController
{
    /**
     * @Route(path="/api/events", methods={"GET"})
     * @Get(
     *     path="/api/events",
     *     tags={"События"},
     *     summary="Глобальный список событий",
     *     @Response(response=200, description="")
     * )
     * @param UserEventsFinder $eventsFinder
     * @return array
     */
    public function list(UserEventsFinder $eventsFinder)
    {
        return $eventsFinder->findAll(10);
    }
}
