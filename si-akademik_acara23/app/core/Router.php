<?php
class Router {
    // Peta sederhana: nama resource pada URL => nama class Controller
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'buku'      => 'BukuController', // Tugas Praktik Mandiri 1
        'register'  => 'AuthController',  // Acara 19: autentikasi
        'login'     => 'AuthController',  // Acara 20: login
    ];

    public function dispatch(): void {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';
        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();

        // login dan register bukan resource CRUD: hanya menerima POST
        if ($resource === 'login' || $resource === 'register') {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                Response::json(405, false, 'Method tidak diizinkan');
            }
            $controller->$resource();
            return;
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': $controller->index(); break;
            case 'POST': $controller->store(); break;
            case 'PUT': $controller->update($_GET['id'] ?? null); break;
            case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
            default:
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}
