<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{


    public function index(): void
    {
        $daftarMahasiswa = Mahasiswa::all();

        $this->render(
            __DIR__ . '/../Views/mahasiswa/index.php',
            ['daftarMahasiswa' => $daftarMahasiswa],
            'mahasiswa'
        );
    }

    public function create(): void
    {
        echo "Form Tambah Mahasiswa (MahasiswaController::create)";
    }

    /**
     * Menampilkan detail mahasiswa berdasarkan NIM yang ditangkap dari URL.
     * Contoh: /mahasiswa/23001 -> $nim = '23001'
     */
    public function show($nim): void
    {
        $mahasiswaDitemukan = Mahasiswa::findByNim($nim);

        $this->render(
            __DIR__ . '/../Views/mahasiswa/detail.php',
            ['mahasiswaDitemukan' => $mahasiswaDitemukan],
            'mahasiswa'
        );
    }
}
