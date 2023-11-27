<?php

declare(strict_types=1);

namespace Quantum\Kernel\Pipeline;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

/**
 * Class that handles a request and outputs a response.
 */
interface Handler
{
    /**
     * Handles a request and produces a response.
     */
    public function handle(Request $request): Response;
}