<?php

declare(strict_types=1);

namespace App\Shared;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionNamedType;

class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}

class ContainerException extends Exception implements ContainerExceptionInterface
{
}

/**
 * Simple PSR-11 container with constructor auto-wiring.
 */
final class Container implements ContainerInterface
{
    /** @var array<string, callable> */
    private array $factories = [];

    /** @var array<string, object> */
    private array $instances = [];

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
        unset($this->instances[$id]);
    }

    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (isset($this->factories[$id])) {
            return $this->instances[$id] = ($this->factories[$id])($this);
        }

        if (class_exists($id)) {
            return $this->instances[$id] = $this->autowire($id);
        }

        throw new NotFoundException("Service '{$id}' is not registered in the container.");
    }

    public function has(string $id): bool
    {
        return isset($this->factories[$id]) || class_exists($id);
    }

    private function autowire(string $class): object
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null || $constructor->getNumberOfParameters() === 0) {
            return new $class();
        }

        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            if ($parameter->isOptional()) {
                try {
                    $arguments[] = $this->resolveParameter($parameter);
                    continue;
                } catch (NotFoundException) {
                    $arguments[] = $parameter->getDefaultValue();
                    continue;
                }
            }

            $arguments[] = $this->resolveParameter($parameter);
        }

        return new $class(...$arguments);
    }

    private function resolveParameter(\ReflectionParameter $parameter): mixed
    {
        $type = $parameter->getType();

        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return $this->get($type->getName());
        }

        throw new NotFoundException(
            "Cannot resolve parameter '{$parameter->getName()}' in '{$parameter->getDeclaringClass()?->getName()}'."
        );
    }
}
