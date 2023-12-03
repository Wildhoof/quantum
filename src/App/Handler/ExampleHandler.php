<?php

declare(strict_types=1);

namespace App\Handler;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

use Quantum\Kernel\Pipeline\Handler;

use Quantum\Quantum;
use Quantum\View;

/**
 * Example handler that sends a simple welcome response.
 */
class ExampleHandler implements Handler
{
    public function __construct(
        private readonly View $view
    ) {}

    /**
     * Handles a request and produces a response.
     */
    public function handle(Request $request): Response
    {
        // Render the view
        $this->view->addData(['version' => Quantum::VERSION]);
        $page = $this->view->render('index');

        // Send the response
        $response = new Response(200);
        $response->setBody($page);
        return $response;
    }
}