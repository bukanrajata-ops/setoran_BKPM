# SI Akademik - Acara 26 (Manajemen Konten, Upload File, dan Database Management)

Perubahan dari Acara 25:
- UBAH  app/models/Mahasiswa.php            -> create() menyimpan foto; update() mengubah foto hanya bila ada foto baru
- UBAH  app/controllers/MahasiswaController.php -> upload foto (exif_imagetype, maks. 2MB, nama unik), unlink() foto lama, hapus foto saat data dihapus
- UBAH  app/core/Router.php                 -> POST ...?_method=PUT diperlakukan sebagai PUT (PHP tidak mengisi $_FILES pada PUT)
- BARU  public/uploads/ (+ .htaccess)       -> penyimpanan foto; skrip dilarang berjalan di folder ini
- BARU  db_acara26.sql                      -> ALTER TABLE mahasiswa ADD COLUMN foto (jalankan SEKALI)
- UBAH  public/views/common.js, dashboard.html, dashboard.js, style.css -> FormData, kolom Foto, pratinjau, validasi awal di klien
- BARU  backup/backup_db.bat, backup/README_BACKUP.md -> backup dan restore, jadwal backup (tugas mandiri 2)
- UBAH  docs/API_DOCUMENTATION.md, docs/openapi.yaml, docs/TEST_CASE.md, postman/ (koleksi ACARA26 = regresi JSON + RBAC)

Langkah menjalankan:
1. Letakkan folder di htdocs (setoran_BKPM-1/si-akademik_acara26). Ubah BASE_URL pada public/views/common.js dan app.js bila nama folder berbeda.
2. Jalankan db_acara26.sql di phpMyAdmin (sekali saja). Akun admin tetap seperti Acara 25 (lihat db_seed_acara25.sql di zip sebelumnya: UPDATE users SET role='admin' WHERE username='budi';).
3. Pastikan folder public/uploads dapat ditulis (di XAMPP Windows biasanya sudah).
4. Buka http://localhost/setoran_BKPM-1/si-akademik_acara26/public/views/login.html

Catatan: backup database tidak memuat gambar; salin juga folder public/uploads.
