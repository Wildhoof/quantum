<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http\Message;

use function array_key_exists;

/**
 * Representation of an incoming, server-side HTTP request.
 */
class ServerRequest extends Request
{
    private array $cookieParams = [];
    private array $queryParams = [];
    private array $parsedBody = [];
    private array $attributes = [];

    public function __construct(Method $method, string $requestTarget) {
        parent::__construct($method, $requestTarget);
    }

    /**
     * Retrieves the request cookie arguments, if any.
     */
    public function getCookieParams(): array {
        return $this->cookieParams;
    }

    /**
     * Return an instance with the specified cookie params.
     */
    public function withCookieParams(array $cookies): ServerRequest
    {
        $clone = clone $this;
        $clone->cookieParams = $cookies;
        return $clone;
    }

    /**
     * Retrieves the deserialized query string arguments, if any.
     */
    public function getQueryParams(): array {
        return $this->queryParams;
    }

    /**
     * Return an instance with the specified query string arguments.
     */
    public function withQueryParams(array $query): ServerRequest
    {
        $clone = clone $this;
        $clone->queryParams = $query;
        return $clone;
    }

    /**
     * Retrieves the request body arguments, if any.
     */
    public function getParsedBody(): array {
        return $this->parsedBody;
    }

    /**
     * Return an instance with the specified request body arguments.
     */
    public function withParsedBody(array $parsedBody): ServerRequest
    {
        $clone = clone $this;
        $clone->parsedBody = $parsedBody;
        return $clone;
    }

    /**
     * Retrieves all derived request arguments.
     */
    public function getAttributes(): array {
        return $this->attributes;
    }

    /**
     * Retrieves a specific derived request argument.
     */
    public function getAttribute(string $name): mixed {
        return $this->attributes[$name];
    }

    /**
     * Add a derived request attribute.
     */
    public function withAttribute(string $name, $value): ServerRequest
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    /**
     * Remove a derived request attribute.
     */
    public function withoutAttribute(string $name): ServerRequest
    {
        if (!array_key_exists($name, $this->attributes)) {
            return $this;
        }

        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }
}
