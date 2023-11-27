<?php

declare(strict_types=1);

namespace Quantum\Kernel\Utility;

use Quantum\Kernel\Http\Request;

use InvalidArgumentException;

use function in_array;
use function strtoupper;
use function trim;

/**
 * Trait containing utility functions for request methods.
 */
trait RequestMethodTrait
{
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
        if (!in_array($method, Request::VALID_METHODS)) {
            throw new InvalidArgumentException(
                'Invalid Request Method ' . $method
            );
        }
    }
}