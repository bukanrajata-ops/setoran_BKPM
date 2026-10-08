<?php
// public/index.php
// Front controller: sekarang cuma bertugas menyiapkan config, routes,
// dan URI, lalu menyerahkan pencocokan route + middleware + pemanggilan
// Controller ke App\Core\Router.
require_once __DIR__ . '/../config/config.php';
// 1. Load daftar rute array dari web.php
$routes = require __DIR__ . '/../routes/web.php';
// 2. Tangkap URI dan Method HTTP
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method     = $_SERVER['REQUEST_METHOD'];
// 3. Potong BASE_URL secara presisi
$uri = $requestUri;
if (defined('BASE_URL') && !empty(BASE_URL)) {
    if (str_starts_with($uri, BASE_URL)) {
        $uri = substr($uri, strlen(BASE_URL));
    }
}
// 4. Serahkan ke Router (app/Core/Router.php) untuk dicocokkan,
//    dijalankan middleware-nya, lalu memanggil Controller yang sesuai.
$router = new App\Core\Router($routes);
$router->dispatch($uri, $method);
