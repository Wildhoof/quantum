<?php

declare(strict_types=1);

namespace Quantum\Kernel\Container;

/**
 * Arguments to be used in Dependency Injection Definitions.
 */
class Argument
{
    public function __construct(
        private readonly mixed $value
    ) {}

    public function getValue(): mixed {
        return $this->value;
    }
}
