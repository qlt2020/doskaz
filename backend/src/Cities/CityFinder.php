<?php


namespace App\Cities;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class CityFinder
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return City[]
     */
    public function findAll(): array
    {
        $cities = $this->initQuery()
            ->orderBy('priority', 'asc')
            ->execute()
            ->fetchAll();

        return array_map(function ($city) {
            return $this->mapCity($city);
        }, $cities);
    }

    public function first(): ?City
    {
        $city = $this->initQuery()
            ->orderBy('priority', 'asc')
            ->execute()->fetch();
        return $city ? $this->mapCity($city) : null;
    }

    public function findByCoordinates(float $lat, float $lon): ?City
    {
        $id = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('cities_geometry')
            ->andWhere('cities_geometry.geometry && ST_MAKEPOINT(:x, :y)')
            ->setParameter('x', $lon)
            ->setParameter('y', $lat)
            ->execute()
            ->fetchColumn();

        if (!$id) {
            return null;
        }

        return $this->mapCity(
            $this->initQuery()
            ->andWhere('id = :id')
            ->setParameter('id', $id)
            ->execute()->fetch()
        );
    }

    private function mapCity(array $data): City
    {
        return new City($data['id'], $data['name'], $this->connection->convertToPHPValue($data['bounds'], 'json'));
    }

    private function initQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select([
                'id',
                'name',
                'JSON_BUILD_ARRAY(JSON_BUILD_ARRAY(ST_YMIN(bbox), ST_XMIN(bbox)), JSON_BUILD_ARRAY(ST_YMAX(bbox), ST_XMAX(bbox))) AS bounds'

            ])->from('cities');
    }
}
