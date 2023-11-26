<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Server;

use Quantum\Kernel\Http\Message\ServerRequest as Request;
use Quantum\Kernel\Http\Message\Response;

/**
 * Base request handler interface.
 */
interface RequestHandlerInterface
{
    /**
     * Handles a request and produces a response.
     */
    public function handle(Request $request): Response;
}