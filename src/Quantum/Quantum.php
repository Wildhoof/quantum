<?php

declare(strict_types=1);

namespace Quantum;

/**
 * Quantum framework.
 */
class Quantum
{
    /**
     * The current version of the Quantum framework package.
     */
    const VERSION = '0.1.0';

    const MAJOR_VERSION = 0;
    const MINOR_VERSION = 1;
    const RELEASE_VERSION = 0;

    /**
     * Adds middleware to the application. This middleware will be run before
     * the route middleware is executed.
     */
    public function middleware(string $middleware): void {

    }

    /**
     * Adds a handler to a route. Returns the Route object containing the handler
     * and all the route middleware.
     */
    public function route(string $pattern, string $handler): void {

    }

    /**
     * Adds a default route to the application.
     */
    public function notFound(string $handler): void {

    }

    /**
     * Adds a custom error handler that is triggered, whenever an Exception
     * or Error is thrown.
     */
    public function error(string $handler): void {

    }

    /**
     * Run the application.
     */
    public function run(): void {

    }
}