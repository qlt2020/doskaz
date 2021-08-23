<?php

namespace App\Infrastructure\DomainEvents;

interface EventProducer
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}
