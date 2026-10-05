<?php
namespace App\Controllers;
use App\Core\Controller;
class HomeController extends Controller
{
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    $content = __DIR__ . '/../Views/dashboard/index.php';
    $active = 'dashboard';
    require __DIR__ . '/../Views/layouts/main.php';
}
}
