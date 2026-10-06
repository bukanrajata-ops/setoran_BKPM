<?php
// app/Core/BaseController.php
// Base Controller (Acara 10 - Inheritance): berisi method yang dipakai
// bersama oleh semua Controller, yaitu view(), redirect(), dan flash().
// Setiap Controller cukup "extends BaseController" tanpa menulis ulang
// method-method tersebut (prinsip DRY).
namespace App\Core;

class BaseController
{
    /**
     * Menampilkan sebuah view.
     *
     * @param string $view      Nama view relatif terhadap app/Views, tanpa
     *                          ekstensi (mis. 'mahasiswa/index').
     * @param array  $data      Data yang di-extract menjadi variabel di view.
     * @param string $active    Menu navbar yang sedang aktif.
     * @param bool   $useLayout true = dibungkus layout utama (header, navbar,
     *                          flash, footer); false = view berdiri sendiri
     *                          (mis. halaman login).
     */
    protected function view(
        string $view,
        array $data = [],
        string $active = '',
        bool $useLayout = true
    ): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        extract($data, EXTR_SKIP);

        $content = __DIR__ . '/../Views/' . $view . '.php';

        if (!$useLayout) {
            require $content;
            return;
        }

        require __DIR__ . '/../Views/layouts/main.php';
    }

    /**
     * Mengarahkan pengguna ke halaman lain di dalam aplikasi.
     *
     * @param string $path Path relatif terhadap BASE_URL (mis. '/mahasiswa').
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    /**
     * Menyimpan flash message ke session (ditampilkan sekali oleh
     * partials/flash.php).
     */
    protected function flash(string $type, string $text): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_message'] = [
            'type' => $type,
            'text' => $text,
        ];
    }
}
