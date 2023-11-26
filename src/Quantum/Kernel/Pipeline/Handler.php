<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;

/**
 * Class that handles a request and outputs a response.
 */
interface Handler
{
    /**
     * Handles a command and produces a result.
     */
    public function handle(Request $request): Response;
}