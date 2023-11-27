<?php

declare(strict_types=1);

namespace Quantum\Kernel\Utility;

use Quantum\Kernel\Pipeline\Handler;

use InvalidArgumentException;

use function class_exists;
use function class_implements;
use function in_array;
use function sprintf;

/**
 * Trait containing a method to validate a handler name string.
 */
trait ValidateHandlerTrait
{
    /**
     * Sets the route request handler.
     */
    protected function validateHandler(string $handler): void
    {
        if (!class_exists($handler)) {
            $message = sprintf('Request handler %s does not exist!', $handler);
            throw new InvalidArgumentException($message);
        }

        if (!in_array(Handler::class, class_implements($handler))) {
            $message = 'Handler must be an implementation of';
            $message .= ' RequestHandlerInterface!';
            throw new InvalidArgumentException($message);
        }
    }
}