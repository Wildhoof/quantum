<?php

declare(strict_types=1);

namespace Quantum\Kernel\Event;

/**
 * Interface for Event classes.
 */
interface EventInterface
{
    /**
     * The function that is called when an event is triggered.
     */
    public function process(array $params = []): void;
}