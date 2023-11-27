<?php

declare(strict_types=1);

namespace Quantum\Kernel\Http;

use function trim;

/**
 * Trait containing a method to normalize the request target string.
 */
trait RequestTargetTrait
{
    /**
     * Takes a raw request and ensures it begins with a slash.
     */
    private function normalizeRequestTarget(string $target) : string {
        return '/' . trim($target, '/');
    }
}