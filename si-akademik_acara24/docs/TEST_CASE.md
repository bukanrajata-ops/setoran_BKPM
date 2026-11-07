# Test Case dan Hasil Pengujian API Sistem Informasi Akademik

Pengujian manual dengan metode **blackbox testing** dan teknik **Equivalence Partitioning** terhadap `AuthController` dan `MahasiswaController`. Pengujian dijalankan dengan Postman (Collection Runner) memakai Collection `postman/ACARA23_TestCase.postman_collection.json`.

## 1. Ruang Lingkup

Endpoint yang diuji berdasarkan peta Router:

| Endpoint | Method | Controller::method | Keamanan |
|---|---|---|---|
| `/register` | POST | `AuthController::register()` | Publik |
| `/login` | POST | `AuthController::login()` | Publik |
| `/mahasiswa` | GET | `MahasiswaController::index()` | Publik |
| `/mahasiswa` | POST | `MahasiswaController::store()` | Token |
| `/mahasiswa?id={id}` | PUT | `MahasiswaController::update()` | Token |
| `/mahasiswa?id={id}` | DELETE | `MahasiswaController::destroy()` | Token |

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
