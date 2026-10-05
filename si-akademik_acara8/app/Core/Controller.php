<?php
// app/Core/Controller.php
// Base Controller: dipakai oleh semua Controller (Home, Auth, Mahasiswa,
// Dosen) supaya proses render tampilan tidak perlu ditulis ulang di
// tiap Controller.
namespace App\Core;
class Controller
{
    /**
    * Render sebuah view di dalam layout utama (header, navbar, flash,
    * konten, footer).
    *
    * @param string $viewPath Path lengkap ke file view (mis. __DIR__ . '/../Views/mahasiswa/index.php')
    * @param array  $data     Data yang akan di-extract jadi variabel di dalam view
    * @param string $active   Menu navbar yang sedang aktif (mahasiswa/dosen)
    */
    protected function render(string $viewPath, array $data = [], string $active = ''): void
    {
        extract($data);
        $content = $viewPath;
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
