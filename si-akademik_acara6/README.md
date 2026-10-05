# SI Akademik — Acara 6 (lanjutan dari project Acara 5 kamu)

Melanjutkan `si-akademik_acara5`, ditambahkan Middleware, Auth (login/logout),
dan flash message.

## Apa yang berubah dari Acara 5

| Bagian | Acara 5 (lama) | Acara 6 (baru) |
|---|---|---|
| Akses halaman | Semua orang bisa akses `/mahasiswa`, `/dosen` | Wajib login dulu, kalau belum otomatis diarahkan ke `/login` |
| routes/web.php | `'/mahasiswa' => ['MahasiswaController', 'index']` | `'/mahasiswa' => ['controller' => ..., 'action' => ..., 'middleware' => ['AuthMiddleware']]` |
| public/index.php | Cuma cocokkan route lalu panggil Controller | Sebelum panggil Controller, jalankan dulu semua `middleware` yang terpasang di route tsb |
| Login | Belum ada | `AuthController` (loginForm, login, logout), kredensial hardcode `admin` / `12345` |
| Feedback ke user | Belum ada | **Flash message** (Tugas Mandiri): "Selamat datang, Admin!" setelah login, "Anda telah logout." setelah logout — muncul sekali lalu hilang |

File yang **ditambah**: `app/Core/Router.php`, `app/Core/Controller.php`,
`app/Core/Model.php`, `app/Core/Middleware/AuthMiddleware.php`,
`app/Controllers/AuthController.php`, `app/Views/auth/login.php`,
`app/Views/partials/flash.php`.

File yang **diubah**: `routes/web.php`, `public/index.php` (sekarang cuma
menyiapkan routes + URI lalu menyerahkan ke `App\Core\Router`),
`app/Controllers/*` (extends `App\Core\Controller`, pakai `render()`),
`app/Models/*` (extends `App\Core\Model`), `app/Views/layouts/main.php`
(sisipkan flash), `navbar.php` (nama user + tombol logout).

## Struktur folder lengkap (sesuai BKPM Acara 6)

Router, base Controller, base Model, dan Middleware sekarang dikumpulkan
di dalam `app/Core/`, mengikuti diagram struktur folder lengkap pada BKPM:

```
app/
├── Controllers/        (HomeController, AuthController, MahasiswaController, DosenController)
├── Models/              (Mahasiswa, Dosen — masing-masing extends App\Core\Model)
├── Views/
├── Core/
│   ├── Router.php       ← dipanggil dari public/index.php
│   ├── Controller.php   ← Base Controller (menyediakan method render())
│   ├── Model.php        ← Base Model
│   └── Middleware/
│       └── AuthMiddleware.php
├── Services/            ← reserved, belum dipakai (lihat .gitkeep)
└── Repositories/        ← reserved, belum dipakai (lihat .gitkeep)
```

`app/Middleware/AuthMiddleware.php` (lokasi lama) sudah dipindah seluruhnya
ke `app/Core/Middleware/AuthMiddleware.php`, namespace-nya jadi
`App\Core\Middleware\AuthMiddleware`. `public/index.php` sekarang jauh
lebih ringkas karena logika pencocokan route + eksekusi middleware +
pemanggilan Controller dipindah ke `App\Core\Router::dispatch()`.

## PENTING sebelum menjalankan

1. Pastikan `mod_rewrite` Apache aktif (kalau belum, lihat catatan di README Acara 5).
2. Sesuaikan `BASE_URL` di `config/config.php` dengan nama folder project kamu.

## Cara Menjalankan & Menguji

| Langkah | Yang terjadi |
|---|---|
| Buka `/mahasiswa` tanpa login | **Redirect otomatis** ke `/login` |
| Buka `/login` | Form login muncul (username `admin`, password `12345`) |
| Login dengan data salah | Alert merah "Username atau password salah." |
| Login dengan data benar | Redirect ke `/mahasiswa`, muncul **alert hijau "Selamat datang, Admin!"** |
| Refresh halaman `/mahasiswa` | Alert tadi **sudah hilang** (flash message cuma tampil sekali) |
| Klik **Logout** di navbar | Redirect ke `/login`, muncul **alert biru "Anda telah logout."** |
| Buka `/mahasiswa/23001` (sudah login) | Detail mahasiswa Andi (Tugas Mandiri Acara 5, tetap jalan & tetap diproteksi) |
| Buka `/dosen`, `/`, `/mahasiswa/create` | Semua ikut diproteksi, redirect ke `/login` kalau belum masuk |

Sudah ditest end-to-end (redirect, validasi salah/benar, flash sekali-tampil,
proteksi semua route sensitif, 404) dan semuanya berjalan sesuai harapan.
