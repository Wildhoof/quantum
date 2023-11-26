<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;

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
    public function pipe(Middleware $middleware): Pipeline
    {
        $this->queue->addToQueue($middleware);

        // Return self for fluent interface
        return $this;
    }

    /**
     * Processes the command or delegates it to the handler.
     */
    public function process(Request $request, Handler $handler): Response {
        return (new Next($this->queue, $handler))->handle($request);
    }

    /**
     * Handles a command and produces a result.
     */
    public function handle(Request $request): Response {
        return $this->process($request, $this->handler);
    }
}
