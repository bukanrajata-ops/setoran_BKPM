<?php
// app/Controllers/DosenController.php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Dosen;

class DosenController extends Controller
{
    public function index(): void
    {
        $daftarDosen = Dosen::getAll();

        $this->render(
            __DIR__ . '/../Views/dosen/index.php',
            ['daftarDosen' => $daftarDosen],
            'dosen'
        );
    }
}
