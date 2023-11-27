<?php

declare(strict_types=1);

namespace Quantum\Kernel\Router;

use Quantum\Kernel\Http\Request;

use Quantum\Kernel\Pipeline\Handler;
use Quantum\Kernel\Pipeline\Middleware;

use Quantum\Kernel\Utility\RequestMethodTrait;
use Quantum\Kernel\Utility\RequestTargetTrait;
use Quantum\Kernel\Utility\ValidateHandlerTrait;

use InvalidArgumentException;

use function class_exists;
use function class_implements;
use function in_array;
use function sprintf;

/**
 * Represents the definition of an HTTP Route.
 */
class Route
{
    use RequestTargetTrait;
    use RequestMethodTrait;
    use ValidateHandlerTrait;

    private readonly string $pattern;
    private readonly string $method;
    private readonly string $handler;

    private array $middleware = [];

    public function __construct(string $pattern, string $method, string $handler)
    {
        $this->validateRequestMethod($method);
        $this->validateHandler($handler);

        $this->pattern = $this->normalizeRequestTarget($pattern);
        $this->method = $this->normalizeRequestMethod($method);
        $this->handler = $handler;
    }

    /**
     * Adds an array of middleware that will be run before the call of the
     * action handler.
     */
    public function addMiddleware(array $middleware): void
    {
        foreach ($middleware as $class) {
            if (!class_exists($class)) {
                $message = sprintf('Middleware %s does not exist!', $class);
                throw new InvalidArgumentException($message);
            }

            if (!in_array(Middleware::class, class_implements($class))) {
                $message = 'Middleware must be an implementation of';
                $message .= ' MiddlewareInterface!';
                throw new InvalidArgumentException($message);
            }
        }

        $this->middleware = $middleware;
    }

    /**
     * Returns the normalized route pattern.
     */
    public function getPattern(): string {
        return $this->pattern;
    }

    /**
     * Return the route request handler.
     */
    public function getHandler(): string {
        return $this->handler;
    }

    /**
     * Returns the route middleware.
     */
    public function getMiddleware(): array {
        return $this->middleware;
    }

    /**
     * Uses the provided Matcher to check whether this Route matches the
     * provided Request and returns the result as a boolean.
     */
    public function matchesRequest(Request $request): bool
    {
        // First check the Request method
        if ($this->method != $request->getRequestMethod()) {
            return false;
        }

        // Finally check the pattern
        return $this->pattern == $request->getRequestTarget();
    }
}
