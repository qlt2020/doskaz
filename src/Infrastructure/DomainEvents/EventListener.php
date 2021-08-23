<?php

namespace App\Infrastructure\DomainEvents;

interface EventListener
{
    public function handle($event);

    public function supports($event): bool;
}
