<?php

namespace App\Core;

use App\Repositories\MahasiswaRepository;

class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = $this->normalize($uri);

        foreach ($this->routes[$method] ?? [] as $routePattern => $routeConfig) {
            $pattern = $this->normalize($routePattern);
            $regex = preg_replace(
                '/\{[a-zA-Z0-9_]+\}/',
                '([a-zA-Z0-9_]+)',
                $pattern
            );
            $regex = '#^' . $regex . '$#';

            if (preg_match($regex, $uri, $params)) {
                array_shift($params);

                $this->runMiddleware(
                    $routeConfig['middleware'] ?? []
                );

                $this->callController($routeConfig, $params);
                return;
            }
        }

        $this->notFound();
    }

    private function normalize(string $path): string
    {
        $path = trim($path, '/');

        return $path === '' ? '/' : '/' . $path;
    }

    private function runMiddleware(array $middlewareList): void
    {
        foreach ($middlewareList as $mwName) {
            $mwClass = "App\\Core\\Middleware\\{$mwName}";

            if (class_exists($mwClass)) {
                (new $mwClass())->handle();
            }
        }
    }

    private function callController(
        array $routeConfig,
        array $params
    ): void {
        $controllerClass = "App\\Controllers\\{$routeConfig['controller']}";
        $action = $routeConfig['action'];

        if (!class_exists($controllerClass) || !method_exists($controllerClass, $action)) {
            $this->notFound();
            return;
        }

        $controller = $this->makeController($controllerClass);

        call_user_func_array(
            [$controller, $action],
            $params
        );
    }

    private function makeController(string $controllerClass): object
    {
        if ($controllerClass === 'App\\Controllers\\MahasiswaController') {
            $database = new Database();
            $repository = new MahasiswaRepository($database);

            return new $controllerClass($repository);
        }

        return new $controllerClass();
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404 - Halaman tidak ditemukan';
    }
}
