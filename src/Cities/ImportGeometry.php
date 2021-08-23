<?php


namespace App\Cities;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportGeometry extends Command
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected static $defaultName = 'app:cities:import-geometry';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $geometry = json_decode(file_get_contents('geometry.json'), true);
        foreach ($geometry['features'] as $item) {
            $this->connection->executeUpdate('INSERT INTO cities_geometry (id, geometry) VALUES (:id, ST_SetSRID(ST_GeomFromGeoJSON(:geometry), 4326))', [
                'id' => $item['properties']['id'],
                'geometry' => json_encode($item['geometry'])
            ]);
        }
    }
}
