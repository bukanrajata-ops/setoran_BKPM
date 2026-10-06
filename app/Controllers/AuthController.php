<?php

namespace App\Controllers;

use App\Core\BaseController;

class AuthController extends BaseController
{
    private const USERNAME = 'admin';
    private const PASSWORD = '12345';

    public function loginForm(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['logged_in'])) {
            $this->redirect('/');
        }

        $this->view('auth/login', ['error' => null], '', false);
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

            $this->flash('success', 'Selamat datang, ' . ucfirst($username) . '!');
            $this->redirect('/');
        }

        $this->view(
            'auth/login',
            ['error' => 'Username atau password salah.'],
            '',
            false
        );
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Hapus hanya data login, session lain (kalau ada) tetap aman
        unset($_SESSION['logged_in'], $_SESSION['user_name']);

        $this->flash('info', 'Anda telah logout.');
        $this->redirect('/login');
    }
}
