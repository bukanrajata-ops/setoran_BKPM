# SI Akademik - Acara 25 (Authentication, Authorization, dan Dashboard Admin)

Perubahan dari Acara 24:
- UBAH  app/core/Auth.php                      -> method requireAdmin() (401 bila token tidak sah, 403 bila role bukan admin)
- UBAH  app/controllers/MahasiswaController.php -> store(), update(), destroy() memakai Auth::requireAdmin()
- BARU  public/views/common.js    -> BASE_URL, pengelola sesi (localStorage), pemanggil API (401 => kembali ke login)
- BARU  public/views/login.html + login.js        -> form login (POST /login), simpan token dan role
- BARU  public/views/dashboard.html + dashboard.js -> ringkasan, tabel, Tambah/Ubah/Hapus, Logout, sembunyikan tombol untuk role user
- BARU  public/views/style.css    -> gaya bersama login dan dashboard
- BARU  public/views/logo.png     -> logo pada halaman login
- UBAH  public/views/index.html + app.js -> tautan "Login admin"; BASE_URL ke folder si-akademik_acara25
- BARU  db_seed_acara25.sql       -> menjadikan akun admin
- BARU  postman/ACARA25_TestCase.postman_collection.json -> 25 test case Acara 23 + TC-26 sampai TC-29 (RBAC)
- UBAH  docs/API_DOCUMENTATION.md, docs/openapi.yaml, docs/TEST_CASE.md, postman/Local.postman_environment.json

Langkah menjalankan:
1. Letakkan folder di htdocs (setoran_BKPM-1/si-akademik_acara25). Ubah BASE_URL pada public/views/common.js dan app.js bila nama folder berbeda.
2. Database.php: gunakan versi milikmu (port 3307) di app/config/.
3. Daftarkan akun lewat POST /register, lalu jalankan db_seed_acara25.sql agar akun tersebut berrole admin.
4. Buka http://localhost/setoran_BKPM-1/si-akademik_acara25/public/views/login.html

Catatan: token berlaku 5 menit. Dashboard menampilkan hitung mundur dan kembali ke login saat sesi habis.
