<?php

namespace App\Infrastructure\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

final class MigrationEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['postGenerateSchema'];
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $args)
    {
        $schema = $args->getSchema();

        if (!$schema->hasNamespace('public')) {
            $schema->createNamespace('public');
        }
    }
}
