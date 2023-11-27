<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http;

/**
 * Abstraction layer for HTTP Requests. Stores the relevant data in an easy
 * to manipulate and mock way without affecting the original Request.
 */
class Request
{
    use RequestTargetTrait;
    use RequestMethodTrait;

    public const VALID_METHODS = [
        'GET', 'POST', 'PUT', 'PATCH',
        'DELETE', 'HEAD', 'OPTIONS',
        'TRACE', 'CONNECT'
    ];

    private string $requestMethod;
    private string $requestTarget;

    private array $queryParams;
    private array $parsedBody;

    public function __construct(
        string $requestMethod,
        string $requestTarget,
        array $queryParams,
        array $parsedBody
    ) {
        $requestMethod = $this->normalizeRequestMethod($requestMethod);
        $this->validateRequestMethod($requestMethod);
        $this->requestMethod = $requestMethod;

        $this->requestTarget = $this->normalizeRequestTarget($requestTarget);

        $this->queryParams = $queryParams;
        $this->parsedBody = $parsedBody;
    }

    /**
     * Return the request method, such as GET or POST.
     */
    public function getRequestMethod(): string {
        return $this->requestMethod;
    }

    /**
     * Return the request target, such as '/' or '/home'.
     */
    public function getRequestTarget(): string {
        return $this->requestTarget;
    }

    /**
     * Return the query params as provided in the query string part of the
     * HTTP request URI. Should return the content of $_GET.
     */
    public function getQueryParams(): array {
        return $this->queryParams;
    }

    /**
     * Return the parsed request body, such as the content of $_POST or the
     * parsed JSON request body.
     */
    public function getParsedBody(): array {
        return $this->parsedBody;
    }
}
