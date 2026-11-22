# Test Case Acara 30 - Penguatan Lingkungan Produksi

Kolom "Lokal" = sudah diuji di sandbox (Apache + PHP 8.3 + MariaDB, struktur mirip hosting: `app/` di dalam root web).
Kolom "Produksi" diisi setelah dijalankan di hosting.

| ID | Skenario | Hasil yang diharapkan | Lokal | Produksi |
|----|----------|-----------------------|-------|----------|
| S-01 | Buka `http://DOMAIN/views/login.html` | 301 ke `https://DOMAIN/views/login.html` | Lulus (301) | |
| S-02 | Buka `https://DOMAIN/views/login.html` | 200, halaman login tampil, gembok aman | Lulus (200, tanpa redirect berulang) | |
| S-03 | GET `https://DOMAIN/mahasiswa` | 200, JSON ringkas (mode production) | Lulus | |
| S-04 | Buka `https://DOMAIN/app/config/Database.php` | 403/404 | Lulus (403) | |
| S-05 | Buka `https://DOMAIN/app/config/credentials.php` dan `app/logs/php_errors.log` | 403/404 | Lulus (403, 403) | |
| S-06 | Picu galat PHP/exception | Pengguna menerima pesan umum; detail hanya di `app/logs/php_errors.log` | Lulus: JSON "Terjadi kesalahan pada server", warning tidak tampil, detail ada di log | |
| S-07 | Kredensial database salah | 500 "Koneksi database gagal" tanpa detail; sebab di log | Lulus | |
| S-08 | `credentials.php` hilang | 500 "Koneksi database gagal"; pulih saat berkas dikembalikan | Lulus | |
| S-09 | Login admin lalu tambah data dari View | 201, data tampil | Lulus lewat API (login, tambah, tanpa token 401, password salah 401) | |
| S-10 | Test case Acara 23 (Postman, env Production) | Lulus seperti di lokal | | |
| S-11 | Simulasi disaster recovery | Tabel kembali, jumlah baris sama | Lulus (lihat `DISASTER_RECOVERY.md`) | |

Catatan: S-01/S-02 di sandbox memakai header `X-Forwarded-Proto: https` untuk meniru HTTPS di belakang proxy; pengujian HTTPS sesungguhnya (sertifikat, gembok) hanya bisa dilakukan di hosting.
