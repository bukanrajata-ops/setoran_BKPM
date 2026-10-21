# SI Akademik - Acara 20 (API authentication dan error handling)

Perubahan dari Acara 19:
- BARU  app/core/Auth.php                    -> JWT HS256 native: generateToken(), validateToken(), requireAuth()
- UBAH  app/controllers/AuthController.php   -> ditambah aksi login()
- UBAH  app/core/Router.php                  -> resource 'login' (hanya POST)
- UBAH  app/controllers/MahasiswaController.php -> Auth::requireAuth() di store(), update(), destroy() (GET tetap publik)
- UBAH  app/models/Mahasiswa.php             -> try-catch PDOException (500) pada create/update/delete
- UBAH  public/.htaccess                     -> meneruskan header Authorization ke PHP (penting di XAMPP/Apache)
- BARU  postman/SI-Akademik-Acara20.postman_collection.json (token otomatis tersimpan ke {{token}} setelah Login)

Tugas mandiri 2: masa berlaku token 5 menit -> Auth::TOKEN_TTL = 300 (app/core/Auth.php).

Database: tidak ada perubahan. Akun budi/rahasia123 dari Acara 19 dipakai untuk login.
Jika belum ada, kirim POST /register terlebih dulu.
Database.php milikmu (port 3307) tidak diubah.

Staging (simulasi): php -S localhost:8080 -t public public/index.php
