# SI Akademik - Acara 18 (JSON encoding dan HTTP status code)

Perubahan dari Acara 17:
- BARU  app/core/Response.php        -> class Response::json() (format response seragam)
- BARU  app/config/config.php        -> konstanta APP_ENV (Tugas Mandiri 2)
- UBAH  app/core/autoload.php        -> memuat config.php
- UBAH  app/core/Router.php          -> 404 dan 405 memakai Response::json()
- UBAH  app/controllers/MahasiswaController.php, BukuController.php -> memakai Response::json(), validasi 422

Database: import db_workshop.sql (sama seperti Acara 17).
Postman: import postman/SI-Akademik-Acara18.postman_collection.json, atur variabel base_url.
