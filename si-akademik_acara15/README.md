# SI Akademik

Sistem Informasi Akademik sederhana berbasis **PHP native (MVC + OOP)** untuk
mata kuliah *BKPM - Workshop SI Web Server (TIF330805)*, Politeknik Negeri Jember.

## Fitur

- Login & logout (session) dengan `AuthMiddleware`
- CRUD **Mahasiswa**, **Prodi**, dan **Mata Kuliah**
- Daftar **Dosen**
- Pencarian mahasiswa berdasarkan NIM / nama
- Validasi data mahasiswa di **Service Layer** (NIM wajib/angka/unik, email valid, prodi tersedia, angkatan, status)
- **API JSON** data mahasiswa (GET daftar, GET per id, POST tambah) - lihat bagian API
- **Flash message** (session) setelah tambah / ubah / hapus, termasuk saat gagal dan saat NIM sudah terdaftar

## Konsep OOP yang diterapkan

| Konsep | Lokasi |
| --- | --- |
| Inheritance (Base Controller) | `app/Core/BaseController.php` |
| Inheritance (Base Model) | `app/Core/BaseModel.php` |
| Repository Pattern | `app/Repositories/MahasiswaRepository.php`, `app/Repositories/ProdiRepository.php` |
| Service Layer + Dependency Injection | `app/Services/MahasiswaService.php` |
| Router + Middleware | `app/Core/Router.php`, `app/Core/Middleware/` |
| Koneksi database (PDO) | `app/Core/Database.php` |

## API Mahasiswa (Acara 15)

Endpoint: `public/api/mahasiswa.php` (juga dapat diakses sebagai `/api/mahasiswa`
bila `mod_rewrite` aktif). Database contoh: `database/si_akademik_acara15.sql`.
Koleksi Postman: `postman/SI-Akademik-Acara15.postman_collection.json`.

| Method | URL | Fungsi | Status |
| --- | --- | --- | --- |
| GET | `/api/mahasiswa.php` | Daftar seluruh mahasiswa | 200 |
| GET | `/api/mahasiswa.php?id=1` | Satu mahasiswa | 200 / 400 / 404 |
| POST | `/api/mahasiswa.php` | Tambah mahasiswa (body JSON) | 201 / 400 / 409 / 422 |

Contoh body POST:

```json
{ "nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com", "prodi_id": 1 }
```

`angkatan` (default dari 2 digit awal NIM) dan `status` (default `aktif`) opsional.
Bentuk response: `{ "success": true, "message": "...", "data": ... }`;
pada kegagalan validasi ditambah `"errors": { "field": "pesan" }`.

## Struktur Folder

```
si-akademik/
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

## Pembagian Tugas (Acara 13)

```
Controller -> mengatur alur (terima request, panggil Service, tentukan response)
Service    -> mengatur logika bisnis dan validasi
Repository -> mengatur query database
```

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
