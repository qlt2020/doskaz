<?php

namespace App\Infrastructure\DomainEvents;

class EventDispatcher
{
    private $listeners;

    /**
     * EventDispatcher constructor.
     * @param $listeners EventListener[]
     */
    public function __construct($listeners)
    {
        $this->listeners = $listeners;
    }

    public function dispatch(array $events)
    {
        foreach ($events as $event) {
            $this->handle($event);
        }
    }

    private function handle($event)
    {
        foreach ($this->listeners as $listener) {
            if ($listener->supports($event)) {
                $listener->handle($event);
            }
        }
    }
}
