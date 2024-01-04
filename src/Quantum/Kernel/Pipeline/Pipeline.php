<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

/**
 * Middleware pipeline.
 */
class Pipeline implements Middleware, Handler
{
    private Queue $queue;
    private Handler $handler;

    public function __construct(Queue $queue, Handler $handler = null)
    {
        $this->queue = $queue;
        $this->handler = $handler;
    }

    /**
     * Add middleware to the middleware queue.
     */
    final public function pipe(Middleware $middleware): Pipeline
    {
        $this->queue->addToQueue($middleware);

        // Return self for fluent interface
        return $this;
    }

    /**
     * Processes the request or delegates it to the handler.
     */
    final public function process(Request $request, Handler $handler): Response {
        return (new Next($this->queue, $handler))->handle($request);
    }

    /**
     * Handles a request and produces a response.
     */
    final public function handle(Request $request): Response {
        return $this->process($request, $this->handler);
    }
}
