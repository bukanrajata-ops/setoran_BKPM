<?php
// app/Controllers/DosenController.php
namespace App\Controllers;

use App\Models\Dosen;

class DosenController
{
    public function index(): void
    {
        $daftarDosen = Dosen::getAll();
        $active  = 'dosen';
        $content = __DIR__ . '/../Views/dosen/index.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }
}
