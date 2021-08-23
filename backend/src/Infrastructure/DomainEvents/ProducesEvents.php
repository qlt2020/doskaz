<?php


namespace App\Infrastructure\DomainEvents;

trait ProducesEvents
{
    private $events = [];

    private function remember($event)
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
