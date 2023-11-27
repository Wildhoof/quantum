<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http;

use InvalidArgumentException;

use function in_array;
use function strtoupper;
use function trim;

/**
 * Abstraction layer for HTTP Requests. Stores the relevant data in an easy
 * to manipulate and mock way without affecting the original Request.
 */
class Request
{
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
     * Normalizes the request method by making sure that they are uppercase
     * and contain no whitespace characters at the end.
     */
    private function normalizeRequestMethod(string $requestMethod): string {
        return strtoupper(trim($requestMethod));
    }

    /**
     * Throws an exception if the method is not valid.
     */
    private function validateRequestMethod(string $method) : void
    {
        if (!in_array($method, self::VALID_METHODS)) {
            throw new InvalidArgumentException(
                'Invalid Request Method ' . $method
            );
        }
    }

    /**
     * Takes a raw request target and ensures it begins with a slash.
     */
    private function normalizeRequestTarget(string $target) : string {
        return '/' . trim($target, '/');
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
