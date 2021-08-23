<?php


namespace App\Cities;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/cities")
 */
class CitiesController
{
    /**
     * @Route(methods={"GET"})
     * @Get(
     *     path="/api/cities",
     *     summary="Список городов",
     *     tags={"Города"},
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(type="array", @Items(ref="#/components/schemas/City"))
     *         )
     *     }
     * )
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return City[]
     */
    public function index(EntityManagerInterface $entityManager, Request $request)
    {
        $query = $entityManager->createQueryBuilder()->from(Cities::class, 'c')
            ->addSelect('c.id')
            ->addSelect('c.name')
            ->addSelect('ST_YMIN(c.bbox) as ymin')
            ->addSelect('ST_XMIN(c.bbox) as xmin')
            ->addSelect('ST_YMAX(c.bbox) as ymax')
            ->addSelect('ST_XMAX(c.bbox) as xmax')
            ->getQuery();

        $query->setHint(
            Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(
            TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $request->getLocale()
        );

        $query->setHint(
            TranslatableListener::HINT_FALLBACK,
            1
        );

        $citites = $query->getArrayResult();

        return array_map(fn ($city) => new City($city['id'], $city['name'], [[(float) $city['ymin'], (float) $city['xmin']], [(float) $city['ymax'], (float) $city['xmax']]]), $citites);
    }

    /**
     * @Route(path="/detect", methods={"GET"})
     * @param Request $request
     * @param CityFinder $cityFinder
     * @return City
     * @throws InvalidDatabaseException
     * @Get(
     *     path="/api/cities/detect",
     *     summary="Определение города",
     *     tags={"Города"},
     *     responses={
     *         @Response(
     *             response="200",
     *             description="",
     *             @JsonContent(ref="#/components/schemas/City")
     *         )
     *     }
     * )
     */
    public function detect(Request $request, CityFinder $cityFinder)
    {
        $dbPath = '/geoip_data/GeoLite2-City.mmdb';
        if (file_exists($dbPath)) {
            $reader = new Reader($dbPath, ['ru']);
            try {
                $record = $reader->city($request->getClientIp());
                return $cityFinder->findByCoordinates($record->location->latitude, $record->location->longitude) ?? $cityFinder->first();
            } catch (AddressNotFoundException | InvalidDatabaseException $exception) {
            }
        }
        return $cityFinder->first();
    }
}
