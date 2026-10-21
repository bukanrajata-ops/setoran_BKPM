# SI Akademik - Acara 19 (Postman dan API authentication)

Perubahan dari Acara 18:
- BARU  app/models/User.php                  -> Model User: create(), findByUsername(), validasi email (Tugas Mandiri 1)
- BARU  app/controllers/AuthController.php   -> aksi register() (password di-hash dengan password_hash)
- UBAH  app/core/Router.php                  -> resource 'register' (hanya POST, selain itu 405)
- BARU  db_acara19_users.sql                 -> hanya tabel users (jika db_workshop sudah ada)
- UBAH  db_workshop.sql                      -> ditambah tabel users
- BARU  postman/SI-Akademik-Acara19.postman_collection.json  (Collection "API Sistem Informasi Akademik (MVC)")
- BARU  postman/Local.postman_environment.json, postman/Staging.postman_environment.json (Tugas Mandiri 2)

Database: jika db_workshop sudah ada dari Acara 17/18, cukup import db_acara19_users.sql.
Gunakan Database.php milikmu sendiri (port 3307) bila perlu; file ini tidak diubah.

Staging (simulasi): dari folder proyek jalankan
    php -S localhost:8080 -t public public/index.php
lalu pilih Environment "Staging" di Postman.
