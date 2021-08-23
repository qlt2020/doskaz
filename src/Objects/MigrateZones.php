<?php
declare(strict_types=1);

namespace App\Objects;

use App\Objects\Adding\Attribute;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrateZones extends Command
{
    protected static $defaultName = 'app:objects:migrate-zones';

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $z = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('objects')
            ->andWhere("zones->>'type' in ('middle', 'full')")
            ->execute()
            ->fetchAll();

        foreach ($z as $object) {
            $zones = json_decode($object['zones'], true);

            $newZones = array_map(function ($zone) {
                if (!is_array($zone)) {
                    return $zone;
                }

                return $zone ? [
                    'type' => $zone['type'],
                    'attributes' => array_filter($zone, function ($attr) {
                        return in_array($attr, Attribute::ATTRIBUTES);
                    })
                ] : null;
            }, $zones);

            $this->connection->update('objects', [
                'zones' => json_encode($newZones)
            ], [
                'id' => $object['id']
            ]);
        }
    }
}
