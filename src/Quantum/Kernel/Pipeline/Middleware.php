<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

/**
 * Middleware to be executed before the handler.
 */
interface Middleware
{
    /**
     * Processes the request or delegates it to the handler.
     */
    public function process(Request $request, Handler $handler): Response;
}