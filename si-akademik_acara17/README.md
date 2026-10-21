# Sistem Informasi Akademik - Acara 17 (API CRUD, MVC PHP Native)

BKPM - Workshop SI Web Server (TIF330805), Politeknik Negeri Jember.

## Cara Menjalankan
1. Letakkan folder ini di `htdocs` (XAMPP), pastikan Apache `mod_rewrite` aktif.
2. Buka phpMyAdmin, import `db_workshop.sql` (membuat database `db_workshop`, tabel `mahasiswa` dan `buku`).
3. Uji dengan Postman/curl pada `http://localhost/sistem-informasi-akademik/public/mahasiswa`.
   Koleksi Postman: `postman/SI-Akademik-Acara17.postman_collection.json`
   (jalankan berurutan pada tabel yang masih kosong).

## Endpoint
| Method | Alamat | Fungsi | Status |
| --- | --- | --- | --- |
| POST | `/mahasiswa` | Tambah | 201, 400, 409 |
| GET | `/mahasiswa` | Semua data | 200 |
| GET | `/mahasiswa?id=1` | Satu data | 200, 404 |
| PUT | `/mahasiswa?id=1` | Ubah | 200, 409 |
| DELETE | `/mahasiswa?id=1` | Hapus | 200 |
| POST/GET/PUT/DELETE | `/buku` | Resource Buku (tugas mandiri) | sama |

Resource tidak terdaftar: 404. Method lain: 405.

## Alur
`.htaccess -> public/index.php -> Router -> Controller -> Model -> MySQL`
