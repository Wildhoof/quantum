<?php

declare(strict_types=1);

namespace Quantum;

use Quantum\Kernel\Container\Container;

use Quantum\Kernel\Http\Request;
use Quantum\Kernel\Http\Response;

use Quantum\Kernel\Pipeline\Handler;
use Quantum\Kernel\Pipeline\Pipeline;
use Quantum\Kernel\Pipeline\Queue;

use Quantum\Kernel\Router\AddMiddlewareTrait;
use Quantum\Kernel\Router\Mapper;
use Quantum\Kernel\Router\Route;

use function explode;

/**
 * Quantum framework.
 */
class Quantum
{
    use AddMiddlewareTrait;

    /**
     * The current version of the Quantum framework package.
     */
    public const VERSION = '0.1.0';

    public const MAJOR_VERSION = 0;
    public const MINOR_VERSION = 1;
    public const RELEASE_VERSION = 0;

    private Mapper $mapper;
    private Container $container;

    public function __construct() {
        $this->mapper = new Mapper();
        $this->container = new Container();
    }

    /**
     * Returns the dependency injection container.
     */
    public function container(): Container {
        return $this->container;
    }

    /**
     * Adds a handler to a route. Returns the Route object containing the handler
     * and all the route middleware. The pattern looks like 'GET /home' or
     * 'POST /delete-account'.
     */
    public function route(string $pattern, string $handler): Route
    {
        // Split into two parts, method (0) and target (1)
        $pieces = explode(' ', $pattern);

        $route = new Route($pieces[1], $pieces[0], $handler);
        $this->mapper->addRoute($route);

        return $route;
    }

    /**
     * Adds a default route to the application.
     */
    public function notFound(string $handler): void {
        $this->mapper->setNotFound($handler);
    }

    /**
     * Create a pipeline with route middleware and the handler
     */
    private function buildPipe(Handler $handler, array $middleware): Pipeline
    {
        $pipeline = new Pipeline(new Queue(), $handler);

        // First add application middleware to pipeline
        foreach ($this->middleware as $class) {
            $mw = $this->container->get($class);
            $pipeline->pipe($mw);
        }

        // Then add route specific middleware to pipeline
        foreach ($middleware as $class) {
            $mw = $this->container->get($class);
            $pipeline->pipe($mw);
        }

        return $pipeline;
    }

    /**
     * Handles a request and sends a response.
     */
    public function handle(Request $request): Response
    {
        $handler = $this->mapper->getNotFound();
        $middleware = [];

        // Search for a matching route and return its action.
        foreach ($this->mapper->getRoutes() as $route)
        {
            if ($route->matchesRequest($request))
            {
                $handler = $route->getHandler();
                $middleware = $route->getMiddleware();
            }
        }

        $handler = $this->container->get($handler);
        $pipeline = $this->buildPipe($handler, $middleware);
        return $pipeline->handle($request);
    }

    /**
     * Run the application and send the response to the client.
     */
    public function run(): void
    {
        $response = $this->handle(Request::createFromGlobals());

        header(sprintf(
            '%s %s %s',
            $_SERVER['SERVER_PROTOCOL'],
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        echo $response->getBody();
    }
}