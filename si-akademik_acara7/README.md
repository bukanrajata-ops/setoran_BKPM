# SI Akademik — Acara 7 (lanjutan dari project Acara 6 kamu)

Melanjutkan `si-akademik_acara6`. Fokus Acara 7: data mahasiswa yang
sebelumnya hardcode di `MahasiswaController` sekarang diambil dari
database MySQL (`si_akademik`) lewat `App\Core\Model` + `App\Models\Mahasiswa`.

## Apa yang berubah dari Acara 6

| Bagian | Acara 6 (lama) | Acara 7 (baru) |
|---|---|---|
| Sumber data mahasiswa | Array hardcode di `MahasiswaController::getDaftarMahasiswa()` | Query ke tabel `mahasiswa` (JOIN `prodi`) via `Mahasiswa::all()` |
| `app/Core/Model.php` | Sengaja masih kosong | Berisi `db()`: koneksi PDO singleton, baca kredensial dari `config/database.php` |
| `config/database.php` | Belum ada | Baru: return array kredensial (host, dbname, username, password, charset) |
| `app/Models/Mahasiswa.php` | Konstruktor `(nim, nama, prodi)`, angkatan dihitung dari 2 digit NIM | Tambah `angkatan` & `status` asli dari kolom database, plus `Mahasiswa::all()` dan `Mahasiswa::findByNim()` |
| Tabel database | Belum ada | `prodi`, `mahasiswa`, `matakuliah` (lihat `sql/si_akademik_acara7.sql`) |
| Kolom `status` | Belum ada | **Tugas Mandiri**: `ALTER TABLE mahasiswa ADD status ENUM('aktif','cuti','lulus') DEFAULT 'aktif'`, ditampilkan sebagai badge di halaman `/mahasiswa` dan detail |

File yang **ditambah**: `config/database.php`, `sql/si_akademik_acara7.sql`.

File yang **diubah**: `app/Core/Model.php` (isi method `db()`),
`app/Models/Mahasiswa.php` (properti `angkatan`/`status` + method
`all()`/`findByNim()`), `app/Controllers/MahasiswaController.php`
(hapus data hardcode, panggil Model), `app/Views/mahasiswa/index.php`
dan `detail.php` (tambah kolom/baris Status).

`MahasiswaController::index()` dan `show()` tidak berubah alurnya sama
sekali dari sisi Router/Controller (tetap lewat `AuthMiddleware`, tetap
pakai `render()`) — yang berubah murni sumber datanya.

## PENTING sebelum menjalankan

1. Import `sql/si_akademik_acara7.sql` ke MySQL/MariaDB (lewat phpMyAdmin
   atau `mysql -u root si_akademik < sql/si_akademik_acara7.sql`).
2. Sesuaikan kredensial di `config/database.php` (host/port, username,
   password) dengan environment kamu (XAMPP/Laragon biasanya port 3306,
   bukan 3307 — sesuaikan sendiri).
3. Sesuaikan `BASE_URL` di `config/config.php` dengan nama folder project
   kamu di `htdocs`.

## Cara Menjalankan & Menguji

| Langkah | Yang terjadi |
|---|---|
| Login lalu buka `/mahasiswa` | Tabel mahasiswa tampil, datanya sudah dari database (bukan array lagi) |
| Cek kolom Angkatan | Nilainya sekarang dari kolom `angkatan` asli di database, bukan hasil hitung dari NIM |
| Cek kolom Status | Badge hijau "Aktif" untuk semua data awal (default dari Tugas Mandiri) |
| Buka `/mahasiswa/23001` | Detail Andi tampil lengkap dengan baris Status |
| Matikan MySQL lalu refresh `/mahasiswa` | Muncul pesan "Koneksi database gagal: ..." dari `Model::db()` |

Sudah ditest end-to-end: data mahasiswa konsisten dengan yang sebelumnya
di-hardcode sejak Acara 4 (Andi, Budi, Citra, Dinda, Waluyo, Asep), hanya
sumbernya saja yang berpindah dari array PHP ke database MySQL.
