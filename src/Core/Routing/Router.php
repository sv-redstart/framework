<?php

namespace Core\Routing;

class Router
{
    /**
     * @var Route[]
     */
    private static array $routes;

    public static function __callStatic(string $name, array $arguments)
    {
        $method = match ($name) {
            'get'       => HttpMethod::GET,
            'post'      => HttpMethod::POST,
            'put'       => HttpMethod::PUT,
            'patch'     => HttpMethod::PACH,
            'delete'    => HttpMethod::DELETE,
        };

        $route = new Route($method, $arguments[0], $arguments[1], $arguments[2]);
        self::$routes[] = $route;

        return $route;
    }

    private function getUri(): string
    {
        if (! empty($_SERVER['REQUEST_URI']))
        {
            $uri = $_SERVER['REQUEST_URI'];

            return trim($uri, '/');
        }

        return '';
    }

    private function executeAction(Route $route)
    {
        $controllerClass = $route->getControllerClass();

        if (class_exists($controllerClass)) {
            call_user_func_array([new $controllerClass, $route->getActionMethod()], $route->getArgs());
        }
    }

    public function run()
    {
        $uri = $this->getUri();

        $method = $_SERVER['REQUEST_METHOD'];
        $args = [];

        foreach (self::$routes as $route) {
            if (preg_match("~{$route->getpattern()}~", $uri, $args) && $route->getMethod()->value === $method) {
                array_shift($args);

                $route->setArgs($args);

                $this->executeAction($route);

                return;
            }
        }
    }
}
