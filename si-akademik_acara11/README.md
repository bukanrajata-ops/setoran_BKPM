# SI Akademik

Sistem Informasi Akademik sederhana berbasis **PHP native (MVC + OOP)** untuk
mata kuliah *BKPM - Workshop SI Web Server (TIF330805)*, Politeknik Negeri Jember.

## Fitur

- Login & logout (session) dengan `AuthMiddleware`
- CRUD **Mahasiswa**, **Prodi**, dan **Mata Kuliah**
- Daftar **Dosen**
- Pencarian mahasiswa berdasarkan NIM / nama

## Konsep OOP yang diterapkan

| Konsep | Lokasi |
| --- | --- |
| Inheritance (Base Controller) | `app/Core/BaseController.php` |
| Inheritance (Base Model) | `app/Core/BaseModel.php` |
| Repository Pattern | `app/Repositories/MahasiswaRepository.php` |
| Router + Middleware | `app/Core/Router.php`, `app/Core/Middleware/` |
| Koneksi database (PDO) | `app/Core/Database.php` |

## Struktur Folder

```
si-akademik_acara11/
├── app/
│   ├── Controllers/
│   ├── Core/            (BaseController, BaseModel, Database, Router, Middleware)
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   └── Views/
├── config/              (config.php, database.php)
├── public/              (index.php, .htaccess, assets/)
└── routes/              (web.php)
```

## Cara Menjalankan

1. Letakkan folder project di `htdocs` (XAMPP) atau `www` (Laragon).
2. Buat database MySQL bernama `si_akademik`, lalu import tabel
   `prodi`, `mahasiswa`, `matakuliah`, dan `dosen` dari praktikum sebelumnya.
3. Sesuaikan koneksi database di `config/database.php`.
4. Sesuaikan `BASE_URL` di `config/config.php` dengan lokasi folder `public/`
   pada komputer Anda.
5. Buka `http://localhost/<path-project>/public` di browser.

Akun login: `admin` / `12345`

## Alur Git yang Dipakai

```
edit kode -> git add -> git commit -> git push -> GitHub
```

| Perintah | Fungsi |
| --- | --- |
| `git add` | memilih perubahan yang akan disimpan |
| `git commit` | menyimpan versi (checkpoint) kode beserta pesannya |
| `git push` | mengirim commit dari laptop ke GitHub |
| `git pull` | mengambil perubahan terbaru dari GitHub |
| `git clone` | mengambil seluruh project dari GitHub untuk pertama kali |
