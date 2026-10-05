<?php
// app/Core/Router.php
// Router dipindah dari public/index.php ke Core agar front controller
// tidak perlu tahu detail pencocokan URL, middleware, dan pemanggilan
// Controller. public/index.php sekarang cukup memanggil Router::dispatch().
namespace App\Core;
class Router
{
    /** @var array Daftar route hasil require routes/web.php */
    private array $routes;
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }
/**
* Cocokkan URI + method ke salah satu route, jalankan middleware-nya
* (jika ada), lalu panggil Controller yang sesuai. Jika tidak ada yang
* cocok, tampilkan 404.
*/
public function dispatch(string $uri, string $method): void
{
    $uri = $this->normalize($uri);
    foreach ($this->routes[$method] ?? [] as $routePattern => $routeConfig) {
        $pattern = $this->normalize($routePattern);
        // Ubah placeholder seperti {nim} menjadi grup regex
        $regex = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        if (preg_match($regex, $uri, $params)) {
            array_shift($params);
            // buang hasil full-match, sisakan parameter saja
            $this->runMiddleware($routeConfig['middleware'] ?? []);
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
private function callController(array $routeConfig, array $params): void
{
    $controllerClass = "App\\Controllers\\{$routeConfig['controller']}";
    $action          = $routeConfig['action'];
    if (class_exists($controllerClass) && method_exists($controllerClass, $action)) {
        $controller = new $controllerClass();
        call_user_func_array([$controller, $action], $params);
        return;
    }
$this->notFound();
}
private function notFound(): void
{
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}
}
