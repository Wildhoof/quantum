<?php

declare(strict_types=1);

namespace Quantum\Kernel\Router;

use RuntimeException;

/**
 * Registers and collects all Routes and therefore functions as the full
 * mapper of the application.
 */
class Mapper
{
    use ValidateHandlerTrait;

    protected array $routes = [];
    protected string $notFound;

    /**
     * Adds a new Route to the end of the route array stack.
     */
    public function addRoute(Route $route): void {
        $this->routes[] = $route;
    }

    /**
     * Returns an array of all registered Routes.
     */
    public function getRoutes(): array {
        return $this->routes;
    }

    /**
     * Set the 404 Not Found fallback route.
     */
    public function setNotFound(string $handler): void
    {
        $this->validateHandler($handler);

        $this->notFound = $handler;
    }

    /**
     * Return the 404 Not Found fallback route.
     */
    public function getNotFound(): string
    {
        if (!isset($this->notFound)) {
            $message = 'Not Found Request Handler is not set!';
            throw new RuntimeException($message);
        }

        return $this->notFound;
    }
}
