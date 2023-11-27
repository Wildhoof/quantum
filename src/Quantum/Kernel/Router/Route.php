<?php

declare(strict_types=1);

namespace Quantum\Kernel\Router;

use Quantum\Kernel\Http\Request;

use Quantum\Kernel\Http\RequestMethodTrait;
use Quantum\Kernel\Http\RequestTargetTrait;

/**
 * Represents the definition of an HTTP Route.
 */
class Route
{
    use AddMiddlewareTrait;
    use RequestTargetTrait;
    use RequestMethodTrait;
    use ValidateHandlerTrait;

    private readonly string $pattern;
    private readonly string $method;
    private readonly string $handler;

    public function __construct(string $pattern, string $method, string $handler)
    {
        $this->validateRequestMethod($method);
        $this->validateHandler($handler);

        $this->pattern = $this->normalizeRequestTarget($pattern);
        $this->method = $this->normalizeRequestMethod($method);
        $this->handler = $handler;
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
