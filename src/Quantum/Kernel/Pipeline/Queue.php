<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;

use function array_shift;

/**
 * A queue of middleware to be executed.
 */
class Queue
{
    private array $middleware = [];

    /**
     * Add a middleware item to the end of the queue.
     */
    public function addToQueue(Middleware $middleware): void {
        $this->middleware[] = $middleware;
    }

    /**
     * Get the next item in the middleware queue.
     */
    public function getFromQueue(): ?Middleware {
        return array_shift($this->middleware);
    }
}
