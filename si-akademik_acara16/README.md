# SI Akademik

Sistem Informasi Akademik sederhana berbasis **PHP native (MVC + OOP)** untuk
mata kuliah *BKPM - Workshop SI Web Server (TIF330805)*, Politeknik Negeri Jember.

## Fitur

- Login & logout (session) dengan `AuthMiddleware`
- CRUD **Mahasiswa**, **Prodi**, dan **Mata Kuliah**
- Daftar **Dosen**
- Pencarian mahasiswa berdasarkan NIM / nama
- Validasi data mahasiswa di **Service Layer** (NIM wajib/angka/unik, email valid, prodi tersedia, angkatan, status)
- **RESTful API** data mahasiswa (GET, POST, PUT, PATCH, DELETE) - lihat bagian API
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

## RESTful API Mahasiswa (Acara 16)

Endpoint: `public/api/mahasiswa.php`, diakses sebagai `/api/mahasiswa` dan
`/api/mahasiswa/{id}` tanpa `/public` (butuh `mod_rewrite`, lihat `.htaccess` di root dan `public/.htaccess`). Bentuk lama
`?id=1` dari Acara 15 tetap diterima. Database contoh: `database/si_akademik_acara16.sql`.
Koleksi Postman (20 request + test otomatis): `postman/SI-Akademik-Acara16.postman_collection.json`.

| Method | URL | Fungsi | Status |
| --- | --- | --- | --- |
| GET | `/api/mahasiswa` | Daftar seluruh mahasiswa | 200 |
| GET | `/api/mahasiswa/{id}` | Satu mahasiswa | 200 / 400 / 404 |
| POST | `/api/mahasiswa` | Tambah mahasiswa | 201 / 400 / 409 / 422 |
| PUT | `/api/mahasiswa/{id}` | Ubah seluruh data | 200 / 400 / 404 / 409 / 422 |
| PATCH | `/api/mahasiswa/{id}` | Ubah sebagian data (tambahan) | 200 / 400 / 404 / 409 / 422 |
| DELETE | `/api/mahasiswa/{id}` | Hapus mahasiswa | 200 / 400 / 404 |
| lainnya | - | Method tidak didukung | 405 |

Contoh body POST / PUT:

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
5. Buka `http://localhost/<path-project>` di browser (tanpa `/public`; `BASE_URL` diisi `/<path-project>`).

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
