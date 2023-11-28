<?php

declare(strict_types=1);

namespace Quantum\Kernel\Container;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

use function sprintf;

/**
 * Represents the definition of a class and its dependencies. By use of the
 * injected Container, it instantiates the class and resolves its dependencies.
 */
class Definition
{
    private array $dependencies = [];

    public function __construct(
        private readonly string $class,
        private readonly Container $container
    ) {}

    /**
     * Add a dependency by providing the registered classes alias. If no alias
     * has been provided, use the full classname instead.
     */
    public function needs(string|Argument $dependency): Definition
    {
        $this->dependencies[] = $dependency;

        // Return the instance for method chaining
        return $this;
    }

    /**
     * Returns a new instance of the described class.
     */
    public function __invoke(): mixed
    {
        $arguments = [];

        // Get all dependencies from the injected container
        foreach ($this->dependencies as $dependency)
        {
            if ($dependency instanceof Argument) {
                $arguments[] = $dependency->getValue();
            } else {
                $arguments[] = $this->container->get($dependency);
            }
        }

        try {
            $reflection = new ReflectionClass($this->class);
        } catch (ReflectionException $e) {
            $message = sprintf('Class %s does not exist', $this->class);
            throw new RuntimeException($message);
        }

        // Create and return a new instance of the class
        try {
            return $reflection->newInstanceArgs($arguments);
        } catch (ReflectionException $e) {
            $message = 'No valid constructor in ' . $this->class;
            throw new RuntimeException($message);
        }
    }
}
