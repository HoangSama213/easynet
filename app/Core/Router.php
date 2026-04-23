<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->map('GET', $path, $handler);
    }

    public function post(string $path, array $handler): void
    {
        $this->map('POST', $path, $handler);
    }

    private function map(string $method, string $path, array $handler): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_-]*)\}#', '(?P<$1>[^/]+)', $path);
        $pattern = $path === '/' ? '/' : rtrim((string) $pattern, '/');
        $this->routes[$method][] = [
            'path' => $path,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = rtrim($request->uri(), '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $route) {
            if (!preg_match($route['pattern'], $uri, $matches)) {
                continue;
            }

            [$controllerClass, $action] = $route['handler'];
            $controller = new $controllerClass($request);
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $controller->{$action}(...array_values($params));

            return;
        }

        http_response_code(404);
        Response::view('dashboard.not-found', ['title' => 'Không tìm thấy trang']);
    }
}
