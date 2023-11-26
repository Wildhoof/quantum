<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http;

use function trim;

/**
 * Abstraction layer for HTTP Requests. Stores the relevant data in an easy
 * to manipulate and mock way without affecting the original Request.
 */
class Request
{
    public readonly string $requestTarget;

    public function __construct(
        string $requestTarget,
        public readonly Method $requestMethod,
        public readonly array $cookieParams,
        public readonly array $queryParams,
        public readonly array $parsedBody,
        public readonly array $attributes
    ) {
        $this->requestTarget = $this->normalizeRequestTarget($requestTarget);
    }

    /**
     * Takes a raw request target and ensures it begins with a slash.
     */
    private function normalizeRequestTarget(string $target) : string {
        return '/' . trim($target, '/');
    }
}
