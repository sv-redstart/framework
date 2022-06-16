<?php

namespace Core\Container;

use Core\Container\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionException;

class ServiceContainer
{
    /**
     * @var array
     */
    private array $services = [];

    /**
     * @param $id
     * @return object
     * @throws NotFoundException|ReflectionException
     */
    public function get($id): object
    {
        $item = $this->resolve($id);

        if (! ($item instanceof ReflectionClass)) {
            return $item;
        }

        return $this->getInstance($item);
    }

    /**
     * @param $id
     * @return bool
     */
    public function has($id): bool
    {
        try {
            $item = $this->resolve($id);
        } catch (NotFoundException) {
            return false;
        }

        if ($item instanceof ReflectionClass) {
            return $item->isInstantiable();
        }

        return isset($item);
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value): static
    {
        $this->services[$key] = $value;

        return $this;
    }

    /**
     * @param $id
     * @return object|null
     * @throws NotFoundException
     */
    protected function resolve($id): ?object
    {
        try {
            $name = $id;

            if (isset($this->services[$id])) {
                $name = $this->services[$id];
                if (is_callable($name)) {
                    return $name();
                }
            }

            return (new ReflectionClass($name));
        } catch (ReflectionException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param ReflectionClass $item
     * @return object|null
     * @throws NotFoundException
     * @throws ReflectionException
     */
    protected function getInstance(ReflectionClass $item): ?object
    {
        $constructor = $item->getConstructor();
        if (is_null($constructor) || $constructor->getNumberOfRequiredParameters() == 0) {
            return $item->newInstance();
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            if ($type = $param->getType()) {
                $params[] = $this->get($type->getName());
            }
        }

        return $item->newInstanceArgs($params);
    }
}
