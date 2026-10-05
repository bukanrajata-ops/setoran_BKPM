<?php
namespace App\Controllers;

class AuthController
{
    private const USERNAME = 'admin';
    private const PASSWORD = '12345';

    public function loginForm(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!empty($_SESSION['logged_in'])) {
            header('Location: ' . BASE_URL . '/mahasiswa');
            exit;
        }

        $error = null;
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === self::USERNAME && $password === self::PASSWORD) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = $username;

            // Tugas Mandiri: flash message setelah login sukses
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'text' => 'Selamat datang, ' . ucfirst($username) . '!',
            ];

            header('Location: ' . BASE_URL . '/mahasiswa');
            exit;
        }

        $error = 'Username atau password salah.';
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Hapus hanya data login, session lain (kalau ada) tetap aman
        unset($_SESSION['logged_in'], $_SESSION['user_name']);

        // Tugas Mandiri: flash message setelah logout
        $_SESSION['flash_message'] = [
            'type' => 'info',
            'text' => 'Anda telah logout.',
        ];

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
