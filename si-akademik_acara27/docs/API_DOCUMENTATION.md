# Dokumentasi API Sistem Informasi Akademik

Dokumen ini menjelaskan seluruh endpoint REST API Sistem Informasi Akademik (PHP Native, arsitektur MVC). Semua contoh response diambil dari hasil pengujian nyata pada Acara 19 sampai 21.

**Base URL:** `http://localhost/setoran_BKPM-1/si-akademik_acara26/public`

## 1. Ringkasan Endpoint

| No | Method | URL | Controller::method | Autentikasi | Status sukses |
|---|---|---|---|---|---|
| 1 | `POST` | `/register` | `AuthController::register()` | Publik | 201 |
| 2 | `POST` | `/login` | `AuthController::login()` | Publik | 200 |
| 3 | `GET` | `/mahasiswa` | `MahasiswaController::index()` | Publik | 200 |
| 4 | `GET` | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Publik | 200 |
| 5 | `POST` | `/mahasiswa` | `MahasiswaController::store()` | Token (admin) | 201 |
| 6 | `PUT` | `/mahasiswa?id={id}` | `MahasiswaController::update()` | Token (admin) | 200 |
| 7 | `DELETE` | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | Token (admin) | 200 |
| 8 | `POST` | `/buku` | `BukuController::store()` | Publik | 201 |
| 9 | `GET` | `/buku` | `BukuController::index()` | Publik | 200 |
| 10 | `PUT` | `/buku?id={id}` | `BukuController::update()` | Publik | 200 |
| 11 | `DELETE` | `/buku?id={id}` | `BukuController::destroy()` | Publik | 200 |

Endpoint nomor 8 sampai 11 (`/buku`) adalah tambahan latihan dan belum diproteksi token.

## 2. Peta Router

Router membaca segmen terakhir URL sebagai nama resource lalu meneruskannya ke Controller yang sesuai.

| Resource (URL) | Controller | Keterangan |
|---|---|---|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |
| `/buku` | `BukuController` | CRUD data buku (tambahan latihan) |

Method HTTP dipetakan oleh Router: `GET` ke `index()`, `POST` ke `store()`, `PUT` ke `update()`, `DELETE` ke `destroy()`. `/login` dan `/register` hanya menerima `POST`.

## 3. Ketentuan Umum

### 3.1 Format Response
Seluruh response berformat JSON dengan struktur yang sama:

```json
{
  "success": true,
  "message": "Pesan hasil proses",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

Field `errors` (objek berisi pesan per field) hanya muncul bila ada kesalahan validasi.

### 3.2 Header

| Header | Nilai | Keterangan |
|---|---|---|
| `Content-Type` | `application/json` | Seluruh request yang memiliki body |
| `Authorization` | `Bearer <token>` | Endpoint yang bertanda Token |

### 3.3 Autentikasi
1. Daftar lewat `POST /register` (sekali saja), lalu masuk lewat `POST /login`.
2. Ambil `data.token` dari response login.
3. Kirim token pada header `Authorization: Bearer <token>` untuk endpoint bertanda Token.
4. Token berlaku **5 menit** (300 detik). Setelah itu server menjawab `401` dan pengguna harus login ulang.

**Otorisasi (Acara 25).** Payload token memuat `role` (`admin` atau `user`). Operasi tulis pada `/mahasiswa` (POST, PUT, DELETE) dijaga `Auth::requireAdmin()`: token sah dengan role `user` dijawab `403 Forbidden` dengan pesan `Akses ditolak, hanya admin yang diizinkan`. Pembacaan (`GET`) tetap publik. Akun admin dibuat dengan mengubah kolom `role` pada tabel `users` (lihat `db_seed_acara25.sql`); `POST /register` selalu menghasilkan role `user`.

Pesan `401` pada endpoint terproteksi: `Token tidak ditemukan` (header tidak ada) dan `Token tidak valid atau kedaluwarsa` (token diubah atau melewati masa berlaku).

### 3.4 Kode Status

| Kode | Arti |
|---|---|
| 200 OK | Permintaan berhasil |
| 201 Created | Data baru berhasil dibuat |
| 400 Bad Request | Field wajib kosong |
| 401 Unauthorized | Token/kredensial tidak ada atau tidak sah |
| 403 Forbidden | Token sah, tetapi role tidak berhak (bukan admin) |
| 404 Not Found | Data atau endpoint tidak ditemukan |
| 405 Method Not Allowed | Method HTTP tidak didukung endpoint |
| 409 Conflict | Data duplikat (username, email, NIM) |
| 422 Unprocessable Entity | Format data tidak valid (email, NIM) |
| 500 Internal Server Error | Kesalahan pada server atau database |

### 3.5 CORS

Front Controller (`public/index.php`) mengirim header CORS pada seluruh response, termasuk response kesalahan:

```
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization
```

Request preflight (`OPTIONS`) dijawab `204 No Content` tanpa body dan tidak diteruskan ke Controller. Dengan demikian halaman web pada origin lain (misalnya `http://localhost:5500`) dapat memanggil API ini lewat `fetch()`.

## 4. Endpoint Autentikasi

### POST /register

**Controller:** `AuthController::register()`  
**Autentikasi:** Tidak diperlukan (publik)

Mendaftarkan pengguna baru. Kata sandi disimpan sebagai hash (`password_hash()`), dan hash tidak pernah dikembalikan.

**Headers:**

```
Content-Type: application/json
```

**Request body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `username` | string | Ya | Nama pengguna, harus unik |
| `email` | string | Ya | Format email valid (`filter_var`), harus unik |
| `password` | string | Ya | Kata sandi asli, di-hash sebelum disimpan |

Contoh:

```json
{
  "username": "budi",
  "email": "budi@example.com",
  "password": "rahasia123"
}
```

**Contoh response:**

**201 Created** - Registrasi berhasil

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "id": "1"
  },
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**400 Bad Request** - Field kosong

```json
{
  "success": false,
  "message": "Username, email, dan password wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "username": "Field username wajib diisi",
    "email": "Field email wajib diisi",
    "password": "Field password wajib diisi"
  }
}
```

**422 Unprocessable Entity** - Format email tidak valid

```json
{
  "success": false,
  "message": "Format email tidak valid",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "email": "Format email tidak valid"
  }
}
```

**409 Conflict** - Username sudah terdaftar

```json
{
  "success": false,
  "message": "Username sudah terdaftar",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "username": "Username sudah terdaftar"
  }
}
```

**409 Conflict** - Email sudah terdaftar

```json
{
  "success": false,
  "message": "Email sudah terdaftar",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "email": "Email sudah terdaftar"
  }
}
```

**405 Method Not Allowed** - Method bukan POST

```json
{
  "success": false,
  "message": "Method tidak diizinkan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

### POST /login

**Controller:** `AuthController::login()`  
**Autentikasi:** Tidak diperlukan (publik)

Memverifikasi kata sandi dengan `password_verify()` dan menerbitkan token JWT yang berlaku 5 menit.

**Headers:**

```
Content-Type: application/json
```

**Request body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `username` | string | Ya | Nama pengguna terdaftar |
| `password` | string | Ya | Kata sandi |

Contoh:

```json
{
  "username": "budi",
  "password": "rahasia123"
}
```

**Contoh response:**

**200 OK** - Login berhasil

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIs...<token JWT>",
    "role": "user"
  },
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**400 Bad Request** - Field kosong

```json
{
  "success": false,
  "message": "Username dan password wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "username": "Field username wajib diisi",
    "password": "Field password wajib diisi"
  }
}
```

**401 Unauthorized** - Password salah

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**401 Unauthorized** - Username tidak terdaftar

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**405 Method Not Allowed** - Method bukan POST

```json
{
  "success": false,
  "message": "Method tidak diizinkan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```


## 5. Endpoint Mahasiswa

Method `GET` bersifat publik. Method `POST`, `PUT`, dan `DELETE` memerlukan token dengan role admin (`Auth::requireAdmin()`).

**Foto mahasiswa (Acara 26).** Setiap mahasiswa dapat memiliki satu foto. `POST` dan pembaruan menerima dua jenis body: JSON (tanpa foto, seperti sebelumnya) atau `multipart/form-data` (dengan berkas pada field `foto`). Berkas disimpan di `public/uploads/` dengan nama baru yang unik (`foto_<uniqid>.jpg|png`), dan kolom `foto` pada response berisi nama berkas itu atau `null`. Alamat gambar: `{base_url}/uploads/{foto}`.

| Aturan foto | Keterangan |
|---|---|
| Format | JPG atau PNG. Ekstensi nama berkas dan signature isi berkas (`exif_imagetype()`) harus sama-sama JPG/PNG dan cocok satu sama lain |
| Ukuran | Maksimal 2 MB |
| Opsional | Tanpa berkas, data tetap tersimpan dengan `foto` = `null` (tambah) atau foto lama dipertahankan (ubah) |
| Penggantian | Foto baru pada pembaruan menghapus berkas foto lama dengan `unlink()`; menghapus data juga menghapus berkas fotonya |
| Kesalahan | `422` dengan `errors.foto`: "Berkas harus berupa gambar JPG/PNG yang valid" atau "Ukuran berkas maksimal 2MB" |

Pembaruan dengan berkas: PHP hanya mengisi `$_POST` dan `$_FILES` untuk request `POST`, sehingga pembaruan multipart dikirim sebagai `POST /mahasiswa?id={id}&_method=PUT`. Nilai `_method` yang diakui hanya `PUT`. `PUT` dengan body JSON tetap berlaku seperti sebelumnya.

### GET /mahasiswa

**Controller:** `MahasiswaController::index()`  
**Autentikasi:** Tidak diperlukan (publik)

Mengambil seluruh data mahasiswa (urut id terbaru lebih dulu).

**Contoh response:**

**200 OK** - Semua data

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 2,
      "nim": "220100002",
      "nama": "Siti Aminah",
      "prodi": "Sistem Informasi",
      "foto": "foto_6720b1c2d3e4f5.12345678.png",
      "created_at": "2026-10-21 10:00:00"
    },
    {
      "id": 1,
      "nim": "220100001",
      "nama": "Budi Santoso",
      "prodi": "Teknik Informatika",
      "foto": null,
      "created_at": "2026-10-21 10:00:00"
    }
  ],
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

### GET /mahasiswa?id={id}

**Controller:** `MahasiswaController::index()`  
**Autentikasi:** Tidak diperlukan (publik)

Mengambil satu data mahasiswa berdasarkan id.

**Query parameter:**

| Parameter | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | Id mahasiswa |

**Contoh response:**

**200 OK** - Satu data (id=1)

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {
    "id": 1,
    "nim": "220100001",
    "nama": "Budi Santoso",
    "prodi": "Teknik Informatika",
    "created_at": "2026-10-21 10:00:00"
  },
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**404 Not Found** - Data tidak ditemukan (id=999)

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

### POST /mahasiswa

**Controller:** `MahasiswaController::store()`  
**Autentikasi:** Diperlukan, role admin (header `Authorization: Bearer <token>`)

Menambah data mahasiswa baru. Urutan validasi: token (401), role admin (403), field kosong (400), format NIM (422), berkas foto (422), NIM duplikat (409). Berkas yang sudah tersimpan dihapus kembali bila data ditolak karena NIM duplikat.

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nim` | string | Ya | Tepat 9 karakter, harus unik |
| `nama` | string | Ya | Nama mahasiswa |
| `prodi` | string | Ya | Program studi |
| `foto` | file | Tidak | Hanya pada body `multipart/form-data`: JPG/PNG, maks. 2 MB |

Contoh:

```json
{
  "nim": "220100001",
  "nama": "Budi Santoso",
  "prodi": "Teknik Informatika"
}
```

Contoh dengan foto (`multipart/form-data`; jangan mengatur header `Content-Type` secara manual, klien menambahkan boundary sendiri):

```bash
curl -X POST "{base_url}/mahasiswa" \
  -H "Authorization: Bearer <token>" \
  -F "nim=220100001" -F "nama=Budi Santoso" -F "prodi=Teknik Informatika" \
  -F "foto=@budi.png"
```

**Contoh response:**

**201 Created** - Data berhasil ditambahkan

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": {
    "id": "1"
  },
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**400 Bad Request** - Field wajib kosong

```json
{
  "success": false,
  "message": "Field nim, nama, dan prodi wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "nim": "Field nim wajib diisi",
    "nama": "Field nama wajib diisi",
    "prodi": "Field prodi wajib diisi"
  }
}
```

**403 Forbidden** - Token sah, role bukan admin

```json
{
  "success": false,
  "message": "Akses ditolak, hanya admin yang diizinkan",
  "data": null,
  "timestamp": "2026-11-07T10:00:00+07:00"
}
```

**401 Unauthorized** - Tanpa token

```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**401 Unauthorized** - Token tidak valid

```json
{
  "success": false,
  "message": "Token tidak valid atau kedaluwarsa",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**409 Conflict** - NIM sudah terdaftar

```json
{
  "success": false,
  "message": "NIM sudah terdaftar",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "nim": "NIM sudah terdaftar"
  }
}
```

**422 Unprocessable Entity** - NIM bukan 9 digit

```json
{
  "success": false,
  "message": "Format NIM tidak valid, harus 9 digit",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "nim": "NIM harus 9 digit"
  }
}
```

### PUT /mahasiswa?id={id}

**Controller:** `MahasiswaController::update()`  
**Autentikasi:** Diperlukan, role admin (header `Authorization: Bearer <token>`)

Mengubah data mahasiswa. Urutan validasi: token (401), role admin (403), parameter id (400), data ada (404), field kosong (400), format NIM (422), NIM duplikat (409). Semua field teks harus dikirim lengkap. NIM milik data itu sendiri tidak dihitung duplikat. Foto bersifat opsional: tanpa foto baru, foto lama dipertahankan; dengan foto baru, berkas lama dihapus. Untuk mengirim foto gunakan `POST /mahasiswa?id={id}&_method=PUT` dengan body `multipart/form-data` (lihat bagian 5).

**Query parameter:**

| Parameter | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | Id mahasiswa yang diubah |

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `nim` | string | Ya | Tepat 9 karakter, unik terhadap data lain |
| `nama` | string | Ya | Nama mahasiswa |
| `prodi` | string | Ya | Program studi |

Contoh:

```json
{
  "nim": "220100001",
  "nama": "Budi Santoso Putra",
  "prodi": "Teknik Informatika"
}
```

**Contoh response:**

**200 OK** - Data berhasil diperbarui

```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**403 Forbidden** - Token sah, role bukan admin

```json
{
  "success": false,
  "message": "Akses ditolak, hanya admin yang diizinkan",
  "data": null,
  "timestamp": "2026-11-07T10:00:00+07:00"
}
```

**401 Unauthorized** - Tanpa token

```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**422 Unprocessable Entity** - NIM bukan 9 digit

```json
{
  "success": false,
  "message": "Format NIM tidak valid, harus 9 digit",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "nim": "NIM harus 9 digit"
  }
}
```

**409 Conflict** - NIM milik data lain

```json
{
  "success": false,
  "message": "NIM sudah terdaftar",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "nim": "NIM sudah terdaftar"
  }
}
```

**404 Not Found** - Data tidak ditemukan (id=999)

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**400 Bad Request** - Field wajib kosong

```json
{
  "success": false,
  "message": "Field nim, nama, dan prodi wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "nim": "Field nim wajib diisi",
    "nama": "Field nama wajib diisi",
    "prodi": "Field prodi wajib diisi"
  }
}
```

**400 Bad Request** - Parameter id kosong

```json
{
  "success": false,
  "message": "Parameter id wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "id": "Parameter id harus berupa bilangan bulat positif"
  }
}
```

### DELETE /mahasiswa?id={id}

**Controller:** `MahasiswaController::destroy()`  
**Autentikasi:** Diperlukan, role admin (header `Authorization: Bearer <token>`)

Menghapus data mahasiswa berdasarkan id. Urutan validasi: token (401), role admin (403), parameter id (400), data ada (404).

**Query parameter:**

| Parameter | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | Id mahasiswa yang dihapus |

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Contoh response:**

**403 Forbidden** - Token sah, role bukan admin

```json
{
  "success": false,
  "message": "Akses ditolak, hanya admin yang diizinkan",
  "data": null,
  "timestamp": "2026-11-07T10:00:00+07:00"
}
```

**401 Unauthorized** - Tanpa token

```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**200 OK** - Data berhasil dihapus

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**404 Not Found** - Data tidak ditemukan (id=999)

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**400 Bad Request** - Parameter id kosong

```json
{
  "success": false,
  "message": "Parameter id wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "id": "Parameter id harus berupa bilangan bulat positif"
  }
}
```


## 6. Endpoint Buku (Tambahan)

Endpoint latihan pada Acara 17 dan 18. Saat ini belum diproteksi token.

### POST /buku

**Controller:** `BukuController::store()`  
**Autentikasi:** Tidak diperlukan (publik)

Menambah data buku (endpoint tambahan latihan, belum diproteksi token).

**Headers:**

```
Content-Type: application/json
```

**Request body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `judul` | string | Ya | Judul buku |
| `penulis` | string | Ya | Nama penulis |
| `tahun` | integer | Ya | Tahun terbit |

Contoh:

```json
{
  "judul": "Pemrograman PHP Dasar",
  "penulis": "Rina Putri",
  "tahun": 2024
}
```

**Contoh response:**

**201 Created** - Data berhasil ditambahkan

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": {
    "id": "1"
  },
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**400 Bad Request** - Field wajib kosong

```json
{
  "success": false,
  "message": "Field judul, penulis, dan tahun wajib diisi",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00",
  "errors": {
    "penulis": "Field penulis wajib diisi",
    "tahun": "Field tahun wajib diisi"
  }
}
```

### GET /buku

**Controller:** `BukuController::index()`  
**Autentikasi:** Tidak diperlukan (publik)

Mengambil seluruh data buku, atau satu data bila parameter `id` dikirim.

**Query parameter:**

| Parameter | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Tidak | Id buku; bila kosong, seluruh data dikembalikan |

**Contoh response:**

**200 OK** - Semua data

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 1,
      "judul": "Pemrograman PHP Dasar",
      "penulis": "Rina Putri",
      "tahun": 2024,
      "created_at": "2026-10-21 10:00:00"
    }
  ],
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**404 Not Found** - Data tidak ditemukan (id=999)

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

### PUT /buku?id={id}

**Controller:** `BukuController::update()`  
**Autentikasi:** Tidak diperlukan (publik)

Mengubah data buku (belum diproteksi token).

**Query parameter:**

| Parameter | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | Id buku |

**Headers:**

```
Content-Type: application/json
```

**Request body:**

| Field | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `judul` | string | Ya | Judul buku |
| `penulis` | string | Ya | Nama penulis |
| `tahun` | integer | Ya | Tahun terbit |

Contoh:

```json
{
  "judul": "Pemrograman PHP Dasar (Edisi 2)",
  "penulis": "Rina Putri",
  "tahun": 2025
}
```

**Contoh response:**

**200 OK** - Data berhasil diperbarui

```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

### DELETE /buku?id={id}

**Controller:** `BukuController::destroy()`  
**Autentikasi:** Tidak diperlukan (publik)

Menghapus data buku (belum diproteksi token).

**Query parameter:**

| Parameter | Tipe | Wajib | Keterangan |
|---|---|---|---|
| `id` | integer | Ya | Id buku |

**Contoh response:**

**200 OK** - Data berhasil dihapus

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```


## 7. Kesalahan Umum

Kesalahan berikut dapat terjadi pada semua endpoint.

**404 Not Found** - Endpoint tidak ditemukan (`GET /dosen`)

```json
{
  "success": false,
  "message": "Endpoint tidak ditemukan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**405 Method Not Allowed** - Method tidak didukung (`PATCH /mahasiswa?id=1`)

```json
{
  "success": false,
  "message": "Method tidak diizinkan",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

**500 Internal Server Error** - Kesalahan database (500) (`POST /mahasiswa`)

```json
{
  "success": false,
  "message": "Terjadi kesalahan pada server",
  "data": null,
  "timestamp": "2026-10-21T10:00:00+07:00"
}
```

## 8. Catatan Perilaku dan Keterbatasan

Berikut perilaku yang teramati saat pengujian dan perlu diketahui pengguna API:

1. `PUT` dan `DELETE` pada `/mahasiswa` memeriksa parameter `id` (400 bila kosong atau bukan bilangan bulat positif) dan keberadaan data (404). Perilaku ini diperbaiki pada Acara 23 setelah pengujian blackbox menemukannya sebagai Fail.
2. `PUT` dan `DELETE` pada `/buku` belum memeriksa keberadaan `id` sehingga `id` yang tidak ada tetap dijawab `200`.
3. Endpoint `/buku` belum memerlukan token.
4. Klaim `role` dipakai untuk otorisasi pada `/mahasiswa` sejak Acara 25 (`Auth::requireAdmin()`). `/buku` belum memakainya.
5. Token bersifat stateless sehingga tidak dapat dicabut sebelum kedaluwarsa. Tombol Logout pada dashboard hanya menghapus token di browser; token yang sama tetap sah di server sampai habis masa berlakunya.
6. Dashboard menyimpan token di `localStorage`, sehingga dapat dibaca oleh skrip apa pun yang berjalan pada halaman (risiko XSS). Isi tabel selalu ditulis dengan `textContent` untuk mencegahnya.
7. Folder `public/uploads/` memiliki `.htaccess` yang melarang eksekusi skrip. Pemeriksaan `exif_imagetype()` hanya memastikan signature gambar; berkas yang sah sebagai gambar tetapi disisipi kode di bagian akhirnya akan diterima, dan hanya aman karena disimpan sebagai `.jpg`/`.png` pada folder tanpa eksekusi skrip. Pengamanan yang lebih ketat adalah membuat ulang gambar (misalnya dengan GD) sebelum disimpan.
8. Backup database (Export phpMyAdmin atau `mysqldump`) hanya memuat nama berkas pada kolom `foto`, bukan isi gambarnya. Cadangan lengkap memerlukan salinan folder `public/uploads/`.
9. Nama berkas yang dibaca dari kolom `foto` dipakai lewat `basename()` ketika berkas dihapus, sehingga nilai seperti `../../x` tidak dapat menghapus berkas di luar `public/uploads/`.
10. Batas 2 MB juga ditegakkan oleh PHP (`upload_max_filesize`, `post_max_size`). Berkas yang melewati batas konfigurasi PHP dijawab dengan pesan yang sama, "Ukuran berkas maksimal 2MB".
