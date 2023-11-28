<?php

declare(strict_types=1);

namespace App\Handler;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

use Quantum\Kernel\Pipeline\Handler;

use Quantum\Quantum;

/**
 * Example handler that sends a simple welcome response.
 */
class ExampleHandler implements Handler
{
    /**
     * Handles a request and produces a response.
     */
    public function handle(Request $request): Response
    {
        $response = new Response(200);
        $response->setBody('Welcome to Quantum ' . Quantum::VERSION . '!');
        return $response;
    }
}