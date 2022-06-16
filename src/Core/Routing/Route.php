<?php

namespace Core\Routing;

class Route
{
    private HttpMethod $method;

    private string $pattern;

    private string $controllerClass;

    private string $actionMethod;

    private array $args = [];

    private string $name = '';

    /**
     * @param  HttpMethod  $method
     * @param  string  $pattern
     * @param  string  $controllerClass
     * @param  string  $actionMethod
     */
    public function __construct(HttpMethod $method, string $pattern, string $controllerClass, string $actionMethod)
    {
        $this->method = $method;
        $this->pattern = trim($pattern, '/');
        $this->controllerClass = $controllerClass;
        $this->actionMethod = $actionMethod;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return HttpMethod
     */
    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    /**
     * @return string
     */
    public function getActionMethod(): string
    {
        return $this->actionMethod;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array $args
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }
}
