<?php
require_once __DIR__ . '/../app/Controllers/MahasiswaController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';

// Default halaman: daftar mahasiswa
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : 'mahasiswa';
$segments = explode('/', strtolower($url));

$controllerName = ucfirst($segments[0]) . 'Controller';
$action = $segments[1] ?? 'index';

if (class_exists($controllerName)) {
    $controller = new $controllerName();

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        http_response_code(404);
        echo "Action '{$action}' tidak ditemukan di {$controllerName}.";
    }
} else {
    http_response_code(404);
    echo "Controller '{$controllerName}' tidak ditemukan.";
}
