<?php
namespace App\Controllers;

use App\Models\Mahasiswa;

class MahasiswaController
{

    private function getDaftarMahasiswa(): array
    {
        return [
            new Mahasiswa('23001', 'Andi', 'Teknik Informatika'),
            new Mahasiswa('23002', 'Budi', 'Teknik Informatika'),
            new Mahasiswa('23003', 'Citra', 'Sistem Informasi'),
            new Mahasiswa('23004', 'Dinda', 'Teknik Informatika'),
            new Mahasiswa('23005', 'Waluyo', 'Teknik Informatika'),
            new Mahasiswa('23010', 'Asep', 'Sistem Informasi'),
        ];
    }

    public function index(): void
    {
        $daftarMahasiswa = $this->getDaftarMahasiswa();
        $active  = 'mahasiswa';
        $content = __DIR__ . '/../Views/mahasiswa/index.php';

        require __DIR__ . '/../Views/layouts/main.php';
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
        $mahasiswaDitemukan = null;

        foreach ($this->getDaftarMahasiswa() as $mhs) {
            if ($mhs->getNim() === $nim) {
                $mahasiswaDitemukan = $mhs;
                break;
            }
        }

        $active  = 'mahasiswa';
        $content = __DIR__ . '/../Views/mahasiswa/detail.php';

        require __DIR__ . '/../Views/layouts/main.php';
    }
}
