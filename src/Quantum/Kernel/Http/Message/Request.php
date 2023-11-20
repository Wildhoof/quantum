<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Message;

use function trim;

/**
 * Abstraction layer for HTTP Requests. Stores the relevant data in an easy
 * to manipulate and mock way without affecting the original Request.
 */
class Request extends Message
{
    private string $requestTarget;

    public function __construct(
        private Method $method,
        string $requestTarget
    ) {
        $this->requestTarget = $this->normalizeRequestTarget($requestTarget);
    }

    /**
     * Create and return a clone with overwritten HTTP Request method.
     */
    public function withMethod(Method $method): Request
    {
        $clone = clone $this;
        $clone->method = $method;
        return $clone;
    }

    /**
     * Return the HTTP Request method.
     */
    public function getMethod(): Method {
        return $this->method;
    }

    /**
     * Strips slashes from the end and makes sure a slash will be in front.
     */
    private function normalizeRequestTarget(string $requestTarget): string {
        return '/'. trim($requestTarget, '/');
    }

    /**
     * Create and return a clone with overwritten HTTP Request URI.
     */
    public function withRequestTarget(string $requestTarget): Request
    {
        $clone = clone $this;
        $clone->requestTarget = $this->normalizeRequestTarget($requestTarget);
        return $clone;
    }

    /**
     * Return the HTTP Request target.
     */
    public function getRequestTarget(): string {
        return $this->requestTarget;
    }
}