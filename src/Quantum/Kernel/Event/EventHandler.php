<?php

declare(strict_types=1);

namespace Quantum\Kernel\Event;

use Quantum\Kernel\Container\Container;

use InvalidArgumentException;

use function class_exists;
use function class_implements;
use function in_array;
use function sprintf;
use function usort;

/**
 * Collects and triggers events.
 */
class EventHandler
{
    private array $events = [];

    public function __construct(
        private readonly Container $container
    ) {}

    /**
     * Add a new event to the event collection
     */
    public function attach(string $name, string $event, int $priority = 0): void
    {
        if (!class_exists($event)) {
            $message = sprintf('Event %s does not exist!', $event);
            throw new InvalidArgumentException($message);
        }

        if (!in_array(EventInterface::class, class_implements($event))) {
            throw new InvalidArgumentException('Event must be an implementation of EventInterface!');
        }

        $this->events[$name][] = [
            'priority' => $priority,
            'event' => $event
        ];
    }

    /**
     * Trigger an event.
     */
    public function trigger(string $name, array $params = []): void
    {
        if (isset($this->events[$name])) {
            $event = $this->events[$name];

            usort($event, function ($a, $b) {
                return $b['priority'] <=> $a['priority'];
            });

            foreach ($event as $current) {
                $action = $current['event'];
                $class = $this->container->get($action);
                $class->process($params);
            }
        }
    }
}
