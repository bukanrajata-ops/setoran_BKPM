<?php
// public/index.php
// Acara 5: front controller dengan routing berbasis path URL + .htaccess.
require __DIR__ . '/../config/config.php';

$routes = require __DIR__ . '/../routes/web.php';

// Ambil URI (tanpa query string) dan method HTTP
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Buang BASE_URL dari depan URI, sisakan path relatifnya saja
if (str_starts_with($uri, BASE_URL)) {
    $uri = substr($uri, strlen(BASE_URL)) ?: '/';
}
$uri = rtrim($uri, '/');
if ($uri === '') {
    $uri = '/';
}

// Cocokkan URI ke daftar route, termasuk placeholder seperti {nim}
$matched = false;

foreach ($routes[$method] ?? [] as $routePattern => $handler) {
    $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $routePattern);
    $pattern = '#^' . $pattern . '$#';

    if (preg_match($pattern, $uri, $params)) {
        array_shift($params); // buang hasil full-match, sisakan parameternya saja

        [$controllerName, $action] = $handler;
        $controllerClass = "App\\Controllers\\{$controllerName}";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            call_user_func_array([$controller, $action], $params);
            $matched = true;
            break;
        }
    }
}

if (!$matched) {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}
