<?php

namespace App\Core;

use Closure;
use RuntimeException;

class Router
{
    private array $routes = [];
    private array $middlewareAliases = [];

    public function use(string $alias, callable $handler): void
    {
        $this->middlewareAliases[$alias] = $handler;
    }

    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, array $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, array $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute(string $method, string $uri, array $action): void
    {
        $this->routes[$method][$this->normalize($uri)] = $action;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $normalized = $this->normalize($uri);

        $action = $this->routes[$method][$normalized] ?? null;
        if (!$action) {
            http_response_code(404);
            echo 'Not Found';
            return;
        }

        $middlewareStack = $this->resolveMiddleware($action['middleware'] ?? []);
        $controller = $action['uses'] ?? null;
        if (!$controller) {
            throw new RuntimeException('Route controller not defined.');
        }

        $handler = $this->makeHandler($controller);
        $this->runStack($middlewareStack, $handler);
    }

    private function runStack(array $middlewares, callable $handler): void
    {
        $next = array_reduce(
            array_reverse($middlewares),
            fn ($next, callable $middleware) => function () use ($middleware, $next) {
                return $middleware($next);
            },
            $handler
        );

        $next();
    }

    private function resolveMiddleware(array $aliases): array
    {
        return array_map(function ($alias) {
            if (!isset($this->middlewareAliases[$alias])) {
                throw new RuntimeException("Middleware alias {$alias} not registered");
            }
            return $this->middlewareAliases[$alias];
        }, $aliases);
    }

    private function makeHandler(array|callable $controller): callable
    {
        if ($controller instanceof Closure) {
            return $controller;
        }
        if (is_array($controller) && count($controller) === 2) {
            [$class, $method] = $controller;
            $instance = new $class();
            return fn () => $instance->$method();
        }
        if (is_callable($controller)) {
            return $controller(...);
        }
        throw new RuntimeException('Invalid route handler.');
    }

    private function normalize(string $uri): string
    {
        $uri = '/' . ltrim($uri, '/');
        return rtrim($uri, '/') ?: '/';
    }
}
