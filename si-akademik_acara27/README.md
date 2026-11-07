# SI Akademik - Acara 27 (Integrasi Backend-Frontend)

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
