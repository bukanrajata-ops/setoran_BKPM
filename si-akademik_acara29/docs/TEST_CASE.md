# Test Case dan Hasil Pengujian API Sistem Informasi Akademik

Pengujian manual dengan metode **blackbox testing** dan teknik **Equivalence Partitioning** terhadap `AuthController` dan `MahasiswaController`. Pengujian dijalankan dengan Postman (Collection Runner) memakai Collection `postman/ACARA23_TestCase.postman_collection.json`.

## 1. Ruang Lingkup

Endpoint yang diuji berdasarkan peta Router:

| Endpoint | Method | Controller::method | Keamanan |
|---|---|---|---|
| `/register` | POST | `AuthController::register()` | Publik |
| `/login` | POST | `AuthController::login()` | Publik |
| `/mahasiswa` | GET | `MahasiswaController::index()` | Publik |
| `/mahasiswa` | POST | `MahasiswaController::store()` | Token (admin) |
| `/mahasiswa?id={id}` | PUT | `MahasiswaController::update()` | Token (admin) |
| `/mahasiswa?id={id}` | DELETE | `MahasiswaController::destroy()` | Token (admin) |

## 2. Partisi Ekuivalen

| Variabel masukan | Partisi valid | Partisi tidak valid |
|---|---|---|
| NIM (store, update) | 9 karakter dan unik | kosong; kurang dari 9; lebih dari 9; duplikat |
| Parameter `id` (update, destroy) | bilangan bulat positif dan ada | tidak ada di database; tidak dikirim |
| Header Authorization | token valid | tidak ada |
| Body update | field lengkap | kosong (`{}`) |
| Login | username dan password benar | password salah |
| Email (register) | format valid | tanpa tanda `@` |
| Username (register) | belum terdaftar | sudah terdaftar |

Setiap test case hanya memuat **satu** partisi tidak valid agar penyebab Fail dapat dipastikan.

## 3. Hasil Pengujian

Kolom *Awal* adalah hasil sebelum perbaikan, kolom *Retest* adalah hasil setelah perbaikan pada `MahasiswaController`.

| ID | Skenario | Data uji | Diharapkan | Aktual (awal) | Status awal | Aktual (retest) | Status retest |
|---|---|---|---|---|---|---|---|
| TC-01 | NIM valid dan lengkap | nim 9 digit unik, nama, prodi terisi | 201 | 201 - Data berhasil ditambahkan | Pass | 201 - Data berhasil ditambahkan | Pass |
| TC-02 | NIM kosong (partisi tidak valid) | nim="" | 400 | 400 - Field nim, nama, dan prodi wajib diisi | Pass | 400 - Field nim, nama, dan prodi wajib diisi | Pass |
| TC-03 | NIM duplikat (partisi tidak valid) | nim milik data lain | 409 | 409 - NIM sudah terdaftar | Pass | 409 - NIM sudah terdaftar | Pass |
| TC-04 | Login kredensial benar | username & password benar | 200 | 200 - Login berhasil | Pass | 200 - Login berhasil | Pass |
| TC-05 | Login kredensial salah | password salah | 401 | 401 - Username atau password salah | Pass | 401 - Username atau password salah | Pass |
| TC-06 | Akses store tanpa token | tanpa header Authorization | 401 | 401 - Token tidak ditemukan | Pass | 401 - Token tidak ditemukan | Pass |
| TC-07 | Register data valid | username & email unik, password terisi | 201 | 201 - Registrasi berhasil | Pass | 201 - Registrasi berhasil | Pass |
| TC-08 | Register email tidak valid | email tanpa tanda @ | 422 | 422 - Format email tidak valid | Pass | 422 - Format email tidak valid | Pass |
| TC-09 | Register username duplikat | username sudah terdaftar | 409 | 409 - Username sudah terdaftar | Pass | 409 - Username sudah terdaftar | Pass |
| TC-10 | NIM kurang dari 9 digit | nim=12345 | 422 | 422 - Format NIM tidak valid, harus 9 digit | Pass | 422 - Format NIM tidak valid, harus 9 digit | Pass |
| TC-11 | Ambil seluruh data mahasiswa | tanpa parameter | 200 | 200 - Data berhasil diambil | Pass | 200 - Data berhasil diambil | Pass |
| TC-12 | Ambil satu data (id ada) | id=id data yang ada | 200 | 200 - Data berhasil diambil | Pass | 200 - Data berhasil diambil | Pass |
| TC-13 | Ambil satu data (id tidak ada) | id=999999 | 404 | 404 - Data tidak ditemukan | Pass | 404 - Data tidak ditemukan | Pass |
| TC-14 | Update data valid | id ada, data lengkap, token valid | 200 | 200 - Data berhasil diperbarui | Pass | 200 - Data berhasil diperbarui | Pass |
| TC-15 | Update id tidak ada (partisi id tidak valid) | id=999999, NIM baru yang unik | 404 | 200 - Data berhasil diperbarui | Fail | 404 - Data tidak ditemukan | Pass |
| TC-16 | Update NIM kurang dari 9 digit | nim=12345 | 422 | 422 - Format NIM tidak valid, harus 9 digit | Pass | 422 - Format NIM tidak valid, harus 9 digit | Pass |
| TC-17 | Update NIM milik data lain | nim data kedua | 409 | 409 - NIM sudah terdaftar | Pass | 409 - NIM sudah terdaftar | Pass |
| TC-18 | Update tanpa token | tanpa header Authorization | 401 | 401 - Token tidak ditemukan | Pass | 401 - Token tidak ditemukan | Pass |
| TC-19 | Update body kosong | body {} | 400 | 200 (Fatal error TypeError berupa HTML, tanpa body JSON) | Fail | 400 - Field nim, nama, dan prodi wajib diisi | Pass |
| TC-20 | Destroy id ada | id data kedua, token valid | 200 | 200 - Data berhasil dihapus | Pass | 200 - Data berhasil dihapus | Pass |
| TC-21 | Destroy id tidak ada (partisi id tidak valid) | id=999999 | 404 | 200 - Data berhasil dihapus | Fail | 404 - Data tidak ditemukan | Pass |
| TC-22 | Destroy tanpa token | tanpa header Authorization | 401 | 401 - Token tidak ditemukan | Pass | 401 - Token tidak ditemukan | Pass |
| TC-23 | Destroy tanpa parameter id | tanpa id | 400 | 200 - Data berhasil dihapus | Fail | 400 - Parameter id wajib diisi | Pass |
| TC-24 | Update tanpa parameter id | tanpa id, NIM baru yang unik | 400 | 200 - Data berhasil diperbarui | Fail | 400 - Parameter id wajib diisi | Pass |
| TC-25 | NIM lebih dari 9 digit | nim=1234567890 | 422 | 422 - Format NIM tidak valid, harus 9 digit | Pass | 422 - Format NIM tidak valid, harus 9 digit | Pass |

Catatan hasil yang diharapkan:
- TC-02 menetapkan 400 (modul menuliskan 400/422).
- TC-03 menetapkan **409 Conflict** sesuai spesifikasi API (Acara 18); tabel contoh pada modul menuliskan 422.
- TC-19 pada kode lama: kode status bergantung pengaturan `display_errors`. Pada XAMPP statusnya 200 dengan body HTML berisi jejak kesalahan (path berkas ikut terbuka); pada konfigurasi lain 500. Keduanya Fail karena diharapkan 400 dengan JSON.

## 4. Ringkasan

| Tahap | Total | Pass | Fail |
|---|---|---|---|
| Pengujian awal | 25 | 20 | 5 |
| Retest setelah perbaikan | 25 | 25 | 0 |

## 5. Temuan dan Perbaikan

| Penyebab | Test case Fail | Gejala | Perbaikan |
|---|---|---|---|
| `update()` dan `destroy()` tidak memeriksa keberadaan data | TC-15, TC-21 | `id` yang tidak ada tetap dijawab 200 | Memanggil `Mahasiswa::find()` dan menjawab 404 bila tidak ditemukan |
| Parameter `id` tidak divalidasi | TC-23, TC-24 | Tanpa `id` dijawab 200 | Method `requireId()` menjawab 400 bila `id` kosong atau bukan bilangan bulat positif |
| `update()` tidak memvalidasi field wajib | TC-19 | Body `{}` menghasilkan Fatal error `TypeError` (`nim` bernilai null masuk ke `Mahasiswa::existsByNim()` yang menuntut string); response berupa HTML, bukan JSON | Validasi field kosong (400) dan format NIM (422) seperti pada `store()` sebelum memanggil Model |

Urutan validasi setelah perbaikan: token (401), parameter `id` (400), data ada (404), field kosong (400), format NIM (422), NIM duplikat (409).

Berkas yang diubah: `app/controllers/MahasiswaController.php` (method `update()`, `destroy()`, dan `requireId()`). Dokumentasi `docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml` diperbarui sesuai perilaku baru.

## 6. Tambahan Acara 25: Pengujian RBAC

Sejak Acara 25, `store()`, `update()`, dan `destroy()` memanggil `Auth::requireAdmin()`. Akibatnya 25 test case di atas hanya dapat Pass bila akun login (`budi`) berrole **admin** (`UPDATE users SET role='admin' WHERE username='budi'`, lihat `db_seed_acara25.sql`). Collection yang dipakai: `postman/ACARA25_TestCase.postman_collection.json`.

| ID | Skenario | Data uji | Diharapkan | Aktual | Status |
|---|---|---|---|---|---|
| TC-26 | Login akun role user | akun dari TC-07, password benar | 200, role = user | 200 - Login berhasil, role user | Pass |
| TC-27 | Store dengan token role user | nim 9 digit unik, data lengkap | 403 | 403 - Akses ditolak, hanya admin yang diizinkan | Pass |
| TC-28 | Update dengan token role user | id=1, data lengkap | 403 | 403 - Akses ditolak, hanya admin yang diizinkan | Pass |
| TC-29 | Destroy dengan token role user | id=1 | 403 | 403 - Akses ditolak, hanya admin yang diizinkan | Pass |

Urutan pemeriksaan di server: token (401), role (403), baru validasi data. Karena itu TC-28 dan TC-29 tetap menghasilkan 403 walaupun data dengan `id` tersebut sudah tidak ada.

| Tahap | Total | Pass | Fail |
|---|---|---|---|
| Regresi Acara 23 (akun admin) | 25 | 25 | 0 |
| RBAC Acara 25 | 4 | 4 | 0 |

## 7. Tambahan Acara 26: Upload Foto

Fitur upload diuji terhadap server PHP 8.3 dan MariaDB nyata memakai berkas uji: PNG dan JPG valid, skrip PHP bernama `.png`, berkas `.php`, GIF berekstensi `.png`, PNG berekstensi `.jpg`, PNG 2,3 MB, dan PNG sah yang disisipi kode PHP di ujung berkas. Setiap skenario juga memeriksa isi folder `public/uploads/` (berkas tidak boleh tersimpan bila ditolak, dan tidak boleh ada berkas yatim). Regresi 29 test case Postman (JSON) dijalankan ulang dan tetap Pass.

### 7.1 API (31 skenario)

| ID | Skenario | Status |
|---|---|---|
| A1 | Tambah + foto PNG valid: 201, foto tersimpan di uploads, kolom foto terisi | Pass |
| A2 | Berkas foto dapat diakses browser (GET /uploads/...): 200 image/png | Pass |
| A3 | Tambah + foto JPG valid: 201, ekstensi .jpg | Pass |
| A4 | Berkas sama diunggah dua kali: nama baru berbeda (tidak saling menimpa) | Pass |
| A5 | Form HTML tanpa berkas dipilih (bagian foto kosong, UPLOAD_ERR_NO_FILE): 201, foto = null | Pass |
| A5b | FormData tanpa field foto: 201, foto = null  | Pass |
| A6 | Tambah JSON tanpa foto (kompatibel Acara 25): 201, foto = null  | Pass |
| B1 | Skrip PHP bernama .png ditolak (signature bukan gambar), tidak ada berkas tersimpan | Pass |
| B2 | Berkas .php ditolak | Pass |
| B3 | GIF berekstensi .png ditolak (tipe sebenarnya bukan JPG/PNG) | Pass |
| B4 | PNG berekstensi .jpg ditolak (ekstensi tidak cocok dengan isi) | Pass |
| B5 | Gambar PNG 2,3 MB ditolak (>2MB) | Pass |
| C1 | Token role user mengunggah foto: 403, tidak ada berkas tersimpan | Pass |
| C2 | Tanpa token: 401, tidak ada berkas tersimpan | Pass |
| C3 | NIM 3 digit + foto valid: 422 NIM, tidak ada berkas tersimpan  | Pass |
| C4 | Field kosong + foto valid: 400, tidak ada berkas tersimpan  | Pass |
| C5 | NIM duplikat + foto valid: 409, berkas yang sudah dipindah dihapus (tidak yatim) | Pass |
| D1 | PNG sah dengan kode PHP di ujung berkas diterima sebagai .png dan dilayani sebagai gambar (tidak dieksekusi) | Pass |
| E1 | Ubah dengan foto baru (POST _method=PUT): 200, foto berganti | Pass |
| E2 | Foto lama dihapus dengan unlink() | Pass |
| E3 | Ubah tanpa foto baru: 200, foto lama dipertahankan dan berkasnya masih ada  | Pass |
| E4 | PUT JSON biasa (kompatibel Acara 25): 200, foto dipertahankan  | Pass |
| E5 | Ubah dengan berkas palsu: 422, foto lama utuh, tidak ada berkas baru  | Pass |
| E6 | Ubah ke NIM milik data lain + foto: 409, berkas baru dihapus, foto lama utuh  | Pass |
| E7 | Ubah dengan token role user: 403  | Pass |
| E8 | Ubah id tidak ada: 404, tidak ada berkas tersimpan  | Pass |
| E9 | _method hanya berlaku untuk PUT: POST ..._method=DELETE diperlakukan sebagai tambah data (400), data tidak terhapus  | Pass |
| F1 | Hapus data: 200, baris dan berkas fotonya terhapus  | Pass |
| F2 | Hapus data tanpa foto: 200 (tidak error)  | Pass |
| G1 | Nama foto berisi ../ pada database: hanya basename dipakai, berkas di luar uploads tidak terhapus  | Pass |
| H1 | Setelah semua data dihapus tidak ada berkas yatim di uploads  | Pass |

### 7.2 Tampilan dashboard (11 skenario tambahan, jsdom)

| ID | Skenario | Status |
|---|---|---|
| P1 | Tabel punya kolom Foto; mahasiswa tanpa foto menampilkan penanda "-" | Pass |
| P2 | Memilih foto menampilkan pratinjau | Pass |
| P3 | Tambah dengan foto: dikirim sebagai multipart tanpa Content-Type manual, baris menampilkan thumbnail dari /uploads | Pass |
| P4 | Thumbnail dapat dimuat (200, image/png) | Pass |
| P5 | Berkas bukan gambar: pesan tampil di field foto, tidak ada permintaan ke server | Pass |
| P6 | Gambar 2,5 MB: pesan "Ukuran berkas maksimal 2MB", tidak ada permintaan ke server | Pass |
| P7 | Berkas palsu bertipe image/png: server 422, pesan tampil di field foto, dialog tetap terbuka | Pass |
| P8 | Form Ubah menampilkan foto saat ini sebagai pratinjau | Pass |
| P9 | Ubah dengan foto baru: POST ?id=..&_method=PUT multipart, thumbnail berganti, foto lama terhapus dari server | Pass |
| P10 | Ubah tanpa memilih foto: foto lama dipertahankan | Pass |
| P11 | Role user: thumbnail tampil, tombol aksi tidak ada | Pass |

| Tahap | Total | Pass | Fail |
|---|---|---|---|
| Regresi Postman (JSON, akun admin) | 29 | 29 | 0 |
| Upload foto, API | 31 | 31 | 0 |
| Tampilan dashboard (27 lama + 11 foto) | 38 | 38 | 0 |
