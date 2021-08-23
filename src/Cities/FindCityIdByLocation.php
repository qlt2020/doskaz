<?php


namespace App\Cities;

use App\Objects\Location;
use Doctrine\DBAL\Connection;

class FindCityIdByLocation
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute($lat, $lon): ?int
    {
        return $this->connection->createQueryBuilder()
            ->select('id')
            ->from('cities_geometry')
            ->where('st_contains(cities_geometry.geometry, ST_SetSRID(st_point(:lon, :lat), 4326))')
            ->setParameter('lon', $lon)
            ->setParameter('lat', $lat)
            ->execute()
            ->fetchColumn();
    }
}
