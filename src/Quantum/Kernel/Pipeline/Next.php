<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

/**
 * Handles the next middleware in the pipeline.
 */
class Next implements Handler
{
    private Queue $queue;
    private Handler $handler;

    public function __construct(Queue $queue, Handler $handler)
    {
        $this->queue = clone $queue;
        $this->handler = $handler;
    }

    /**
     * Handles a request and produces a response.
     */
    public function handle(Request $request): Response
    {
        $middleware = $this->queue->getFromQueue();

        if (!is_null($middleware)) {
            return $middleware->process($request, $this);
        }

        return $this->handler->handle($request);
    }
}
