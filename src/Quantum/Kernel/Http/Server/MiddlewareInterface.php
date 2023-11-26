<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Server;

use Quantum\Kernel\Http\Server\RequestHandlerInterface as Handler;

use Quantum\Kernel\Http\Message\ServerRequest as Request;
use Quantum\Kernel\Http\Message\Response;

/**
 * Middleware interface which is a parent to all other middleware classes.
 */
interface MiddlewareInterface
{
    /**
     * Processes the http request or delegates it to the handler.
     */
    public function process(Request $request, Handler $handler): Response;
}
