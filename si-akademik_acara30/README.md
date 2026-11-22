# SI Akademik - Acara 30 (Deployment: Penguatan Lingkungan Produksi)

Dasar: hasil Acara 28 (hosting http://siakademikraja.iceiy.com) + View Acara 29 (escapeHTML, retry, umpan balik).

Perubahan dari Acara 28/29:
- UBAH  public/.htaccess                    -> Langkah 2: redirect seluruh trafik HTTP ke HTTPS (301), aman di belakang proxy (X-Forwarded-Proto)
- UBAH  app/config/config.php               -> Langkah 3: APP_ENV = production; display_errors Off, log_errors On, log ke app/logs/php_errors.log; handler exception berpesan umum
- BARU  app/logs/                           -> lokasi php_errors.log (terlindungi app/.htaccess)
- Langkah 4: app/.htaccess (Require all denied) sudah ada sejak Acara 28 dan tetap dipakai
- UBAH  app/config/Database.php             -> kredensial dibaca dari app/config/credentials.php (pemisahan kredensial dari kode sumber)
- BARU  app/config/credentials.php, credentials.example.php, .gitignore -> credentials.php tidak ikut Git
- UBAH  public/views/app.js, common.js      -> BASE_URL berawalan https://
- UBAH  docs/API_DOCUMENTATION.md, docs/openapi.yaml, postman/Production.postman_environment.json -> base URL produksi https
- BARU  docs/CHECKLIST_AKHIR_ACARA30.md, docs/TEST_CASE_ACARA30.md, docs/DISASTER_RECOVERY.md
- BARU  backup/pulihkan_db.bat              -> tugas mandiri 1: pemulihan dari backup (menolak db_workshop)

PENTING saat unggah ke hosting:
1. Isi password pada app/config/credentials.php (berisi ISI_PASSWORD_HOSTING).
2. Pastikan SSL sudah aktif sebelum .htaccess baru diunggah, kalau tidak situs tidak bisa dibuka.
3. Folder app/logs harus dapat ditulis oleh server.
Lokal (XAMPP): buat app/config/credentials.php berisi host 127.0.0.1, port 3307, database db_workshop, user root, password kosong, lalu ubah APP_ENV ke 'development' bila ingin melihat galat.

---
(Catatan Acara 28 di bawah)

# SI Akademik - Acara 28 (Deployment)

Versi untuk hosting (AeonFree, http://siakademikraja.iceiy.com): `Database.php` berisi kredensial hosting (password diganti `ISI_PASSWORD_HOSTING`), `Auth::SECRET` memakai kunci acak, `BASE_URL` di `public/views/app.js` + `common.js` mengarah ke domain. Pada hosting, `app/` ditempatkan di `htdocs/app` (dilindungi `app/.htaccess`) dan isi `public/` di `htdocs`; `index.php` dan `MahasiswaController::folderUpload()` mendeteksi kedua struktur. Fitur tidak berubah dari Acara 27. Lihat docs/CHECKLIST_DEPLOYMENT.md.

---
(Catatan Acara 27 di bawah)

Backend (Model, Controller, Router, Auth) TIDAK berubah dari Acara 26. Acara 27 hanya menambah dan mengubah View.

Perubahan dari Acara 26:
- BARU  public/views/form_tambah.html + form_tambah.js -> Langkah 1: form tambah (Create), FormData + header Authorization
- BARU  public/views/form_edit.html + form_edit.js     -> Langkah 2: form ubah (Update); id dari query string (form_edit.html?id=3)
- UBAH  public/views/dashboard.html, dashboard.js      -> Langkah 3: tombol Tambah/Ubah menjadi tautan ke halaman form; tombol Hapus + muat ulang tabel tanpa reload penuh
- UBAH  public/views/common.js                         -> Notifikasi (toast), Validasi (sisi View), wajibAdmin(), urlFoto()
- UBAH  public/views/style.css                         -> gaya toast, tautan bergaya tombol, halaman form
- Tugas mandiri 1: notifikasi visual (toast hijau/merah) untuk Create/Update/Delete, berhasil maupun gagal
- Tugas mandiri 2: validasi sisi View (NIM, nama, prodi wajib; NIM 9 karakter; foto JPG/PNG maks. 2 MB) sebelum data dikirim ke Controller

Catatan penting (selisih dengan cuplikan modul):
- Modul memakai method: 'PUT' dengan FormData. PHP tidak mengisi $_POST/$_FILES pada PUT, sehingga data kosong
  dan validasi server menolak (400). Karena itu form ubah mengirim POST ...?id=..&_method=PUT, yang diteruskan
  Router ke MahasiswaController::update() (sama seperti Acara 26).
- Modul memanggil fetch() polos untuk muat data edit dan hapus; di sini dipakai panggilApi() agar token kedaluwarsa (401)
  otomatis kembali ke login dan respons error ditangani.

Langkah menjalankan:
1. Letakkan folder di htdocs (setoran_BKPM-1/si-akademik_acara27). Ubah BASE_URL di public/views/common.js dan app.js bila nama folder berbeda.
2. Database: pakai db_workshop yang sama dengan Acara 26 (kolom foto sudah ada). Bila belum, jalankan db_acara26.sql sekali.
3. Akun admin seperti sebelumnya (UPDATE users SET role='admin' WHERE username='budi';).
4. Buka http://localhost/setoran_BKPM-1/si-akademik_acara27/public/views/login.html
