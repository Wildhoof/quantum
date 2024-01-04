<?php

declare(strict_types=1);

namespace Quantum\Kernel\Container;

/**
 * Arguments to be used in Dependency Injection Definitions.
 */
readonly class Argument
{
    public function __construct(
        private mixed $value
    ) {}

    final public function getValue(): mixed {
        return $this->value;
    }
}
