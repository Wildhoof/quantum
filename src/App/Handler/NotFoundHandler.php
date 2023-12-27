<?php

declare(strict_types=1);

namespace App\Handler;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

use Quantum\Kernel\Pipeline\Handler;

/**
 * Example handler that sends a simple not found response.
 */
final class NotFoundHandler implements Handler
{
    /**
     * Handles a request and produces a response.
     */
    public function handle(Request $request): Response
    {
        $response = new Response(404);
        $response->setBody('Page not found!');
        return $response;
    }
}