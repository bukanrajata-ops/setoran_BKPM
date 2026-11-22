# Checklist Deployment (Acara 28) - mengikuti BKPM

1. Pra-deployment: semua test case Acara 23 Pass; tidak ada kredensial sensitif ter-hardcode di luar `app/config/Database.php` (ganti `Auth::SECRET` dengan kunci acak).
2. phpMyAdmin lokal: ekspor `db_workshop` menjadi `db_workshop.sql` (Export > Quick > SQL).
3. Panel hosting > MySQL Databases: buat database, user, password; Add User To Database > All Privileges. phpMyAdmin hosting: pilih database baru > Import `db_workshop.sql`.
4. File Manager: unggah `app/` di luar `public_html` (sejajar), unggah isi `public/` ke `public_html`. Bila bisa, atur Document Root domain ke `public/`.
5. Konfigurasi: `app/config/Database.php` (host, nama DB, user, password hosting); `BASE_URL` di `public/views/app.js` dan `common.js` = domain hosting.
6. Uji: buka domain di browser; uji ulang sebagian test case Acara 23 lewat Postman (environment Production); buka `domain/app/config/Database.php` harus 404/403.
