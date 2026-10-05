# SI Akademik — Acara 5 (lanjutan dari project Acara 4 kamu)

Project ini melanjutkan `si-akademik_acara4` yang sudah kamu buat (autoloader,
layout+partials, Model `Mahasiswa` dengan `getAngkatan()`), ditambahkan
routing bersih ala Acara 5.

## Apa yang berubah dari Acara 4

| Bagian | Acara 4 (lama) | Acara 5 (baru) |
|---|---|---|
| URL | `?url=Mahasiswa`, `?url=Dosen` (query string) | `/mahasiswa`, `/dosen` (URL bersih) |
| Routing | Array url => path file View langsung | Array url => `[Controller, action]`, mendukung placeholder `{nim}` |
| Logika halaman | Ditulis langsung di `public/index.php` | Dipindah ke `MahasiswaController` & `DosenController` |
| Data dosen | Array biasa di `public/index.php` | Dipindah ke `App\Models\Dosen::getAll()` |
| Detail 1 mahasiswa | Belum ada | **Baru (Tugas Mandiri)**: `/mahasiswa/{nim}` |

File `app/Models/Mahasiswa.php`, `app/Views/layouts/main.php`, dan
`app/Views/partials/*` **tidak diubah strukturnya**, cuma link di
`navbar.php` dan path gambar di `header.php` disesuaikan supaya tetap benar
di URL yang sekarang bisa bertingkat (`/mahasiswa/23001`).

## PENTING sebelum menjalankan

1. **Aktifkan `mod_rewrite`** Apache (uncomment `LoadModule rewrite_module`
   di `httpd.conf` + `AllowOverride All`, lalu restart Apache).
2. **Sesuaikan `BASE_URL`** di `config/config.php` dengan nama folder
   project kamu di htdocs:
   ```php
   define('BASE_URL', '/si-akademik/public');
   ```

## Cara Menjalankan & Menguji

| URL | Yang muncul |
|---|---|
| `.../public/` atau `.../public/mahasiswa` | Tabel daftar mahasiswa (sama seperti Acara 4, + kolom tombol Detail) |
| `.../public/dosen` | Tabel daftar dosen |
| `.../public/mahasiswa/23001` | **Tugas Mandiri**: detail mahasiswa NIM 23001 (Andi) |
| `.../public/mahasiswa/99999` | Detail tidak ditemukan (NIM tidak ada di data) |
| `.../public/asalasal` | `404 - Halaman tidak ditemukan` |

## Kenapa Controller Dipisah dari public/index.php?

Di Acara 4, logika (membuat data mahasiswa/dosen) masih nempel di
`public/index.php` supaya sederhana. Di Acara 5, begitu ada beberapa
halaman dengan parameter berbeda (`index()` vs `show($nim)`), lebih rapi
kalau logikanya dipindah ke Controller masing-masing — `public/index.php`
sekarang isinya murni **routing**, tidak tahu-menahu soal data mahasiswa
atau dosen.
