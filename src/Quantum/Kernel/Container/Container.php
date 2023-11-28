<?php

declare(strict_types=1);

namespace Quantum\Kernel\Container;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

use function array_key_exists;
use function sprintf;

/**
 * Dependency Injection Container
 */
class Container
{
    protected array $definitions = [];
    protected array $singletons = [];
    protected array $interfaces = [];

    /**
     * Defines whether dependencies should be resolved automatically.
     *
     * If auto resolve is set to true, every class that needs to be loaded via
     * Dependency Injection and which has any non-class dependencies must still
     * be declared manually.
     */
    public function __construct(private readonly bool $autoResolve = true)
    {
        // Add the class itself as a singleton
        $this->definitions[Container::class]['singleton'] = true;
        $this->singletons[Container::class] = $this;
    }

    /**
     * Add a definition to the container manually
     */
    public function add(string $alias, string $class = null): Definition
    {
        // If no classname was provided, class is identical to the alias
        $class = $class ?? $alias;

        $definition = new Definition($class, $this);
        $this->definitions[$alias]['definition'] = $definition;

        return $definition;
    }

    /**
     * Return whether a class definition exists or not
     */
    public function has(string $alias): bool {
        return array_key_exists($alias, $this->definitions);
    }

    /**
     * Add a new interface or abstract class to the implementation map.
     */
    public function addInterface(string $parent, string $class): void {
        $this->interfaces[$parent] = $class;
    }

    /**
     * Return whether an interface implementation mapping exists or not.
     */
    public function hasInterface(string $name): bool {
        return array_key_exists($name, $this->interfaces);
    }

    /**
     * Create and add a definition as a singleton to the container. The
     * definition will be returned as in Container::add().
     */
    public function singleton(string $alias, string $class = null): Definition
    {
        // Create a normal definition but mark it as a singleton
        $definition = $this->add($alias, $class);
        $this->definitions[$alias]['singleton'] = true;

        return $definition;
    }

    /**
     * Returns whether a class has been already instantiated through the
     * Dependency Injection Container.
     */
    public function hasSingleton(string $alias): bool {
        return array_key_exists($alias, $this->singletons);
    }

    /**
     * Add an already instantiated class to the singleton array. As the class
     * can no longer be modified through definitions, nothing will be returned.
     */
    public function append(string $alias, object $class): void
    {
        $this->definitions[$alias]['singleton'] = true;
        $this->singletons[$alias] = $class;
    }

    /**
     * Retrieve the class from the container or return a new one
     */
    public function get(string $alias): object
    {
        // Check if alias is an interface and retrieve concrete implementation
        if ($this->hasInterface($alias)) {
            $alias = $this->interfaces[$alias];
        }

        // If it is a singleton, just return the instance
        if ($this->hasSingleton($alias)) {
            return $this->singletons[$alias];
        }

        // If no definition was provided, build it manually
        if ($this->has($alias) === false && $this->autoResolve === true) {
            $this->buildDefinition($alias);
        }

        // Otherwise create a new instance of the newly created instance
        $class = $this->definitions[$alias]['definition']();
        $singleton = $this->definitions[$alias]['singleton'] ?? false;

        // If the instance created is a singleton, add it to the array
        if ($singleton === true) {
            $this->singletons[$alias] = $class;
        }

        return $class;
    }

    /**
     * Resolve a class's dependencies and set Definitions automatically.
     */
    private function buildDefinition(string $alias): void
    {
        $definition = $this->add($alias);

        // Get the constructor parameters
        try {
            $reflector = new ReflectionClass($alias);
            $construct = $reflector->getConstructor();
        } catch (ReflectionException $e) {
            $message = sprintf('Class %s does not exist', $alias);
            throw new RuntimeException($message);
        }

        $parameters = [];

        if ($construct !== null) {
            $parameters = $construct->getParameters();
        }

        foreach ($parameters as $parameter)
        {
            $dependency = $parameter->getType();
            $definition->needs((string) $dependency);
        }
    }
}
