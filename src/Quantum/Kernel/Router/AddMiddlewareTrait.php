<?php

declare(strict_types=1);

namespace Quantum\Kernel\Router;

use Quantum\Kernel\Pipeline\Middleware;

use InvalidArgumentException;

use function class_exists;
use function class_implements;
use function in_array;
use function sprintf;

/**
 * Trait containing a property and method to add and collect middleware.
 */
trait AddMiddlewareTrait
{
    private array $middleware = [];

    /**
     * Adds an array of middleware that will be run before the call of the
     * action handler.
     */
    public function addMiddleware(array $middleware): void
    {
        foreach ($middleware as $class) {
            if (!class_exists($class)) {
                $message = sprintf('Middleware %s does not exist!', $class);
                throw new InvalidArgumentException($message);
            }

            if (!in_array(Middleware::class, class_implements($class))) {
                $message = 'Middleware must be an implementation of';
                $message .= ' MiddlewareInterface!';
                throw new InvalidArgumentException($message);
            }
        }

        $this->middleware = $middleware;
    }
}