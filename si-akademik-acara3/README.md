# SI Akademik — Acara 3 (Versi Minimal Sesuai Studi Kasus)

Ini adalah versi yang **persis mengikuti scope studi kasus Acara 3** di BKPM:
struktur folder MVC dasar dibuat, tapi View **belum dihubungkan ke Controller**
(itu baru dikerjakan mulai Acara 4).

## Struktur Folder

```
si-akademik/
├── public/
│   └── index.php          ← entry point sementara, isinya cuma echo "MVC siap"
├── app/
│   ├── Controllers/        ← masih kosong, baru diisi mulai Acara 4
│   ├── Models/              ← masih kosong, baru diisi mulai Acara 4
│   └── Views/
│       └── mahasiswa/
│           ├── index.php   ← tampilan daftar mahasiswa (statis, Bootstrap 5)
│           └── create.php  ← tampilan form tambah mahasiswa (statis, Bootstrap 5)
├── config/                 ← masih kosong, baru diisi mulai Acara 4
└── routes/                 ← masih kosong, baru diisi mulai Acara 4
```

## Cara Menjalankan / Mengecek Tampilan

Karena routing & Controller belum dibuat pada tahap ini, cara mengecek
tampilan View adalah dengan membukanya **langsung** lewat browser:

1. Salin folder `si-akademik` ke dalam `htdocs` (XAMPP) atau `www` (Laragon).
2. Pastikan Apache sudah *running*.
3. Buka di browser:
   - Entry point: `http://localhost/si-akademik/public/` → akan tampil teks **"MVC siap"**
   - Daftar mahasiswa: `http://localhost/si-akademik/app/Views/mahasiswa/index.php`
   - Form tambah mahasiswa: `http://localhost/si-akademik/app/Views/mahasiswa/create.php`

## Kenapa Datanya Masih Statis / Hardcode?

Karena pada Acara 3, tujuan utamanya adalah **memisahkan struktur folder MVC**
dan **membuat tampilan (View) yang rapi dengan Bootstrap** — bukan membuat
logikanya berfungsi penuh. Dua baris data mahasiswa pada `index.php` hanya
contoh untuk memastikan tabel terlihat profesional; tombol "Tambah Mahasiswa"
dan "Detail" juga belum berfungsi (`action="#"`, `href="#"`).

Baru pada **Acara 4**, View ini akan dihubungkan ke Controller dan Model
sehingga data benar-benar mengalir dari Model → Controller → View, dan
routing lewat `public/index.php` mulai berfungsi (bukan cuma echo "MVC siap").

## Untuk Laporan

Bagian **Hasil** yang bisa kamu tulis:
> "Halaman form dan tabel sudah terlihat profesional dengan Bootstrap 5,
> tampilan sudah rapi dan responsif, dan struktur folder MVC dasar sudah
> siap untuk dihubungkan ke Controller pada pertemuan berikutnya."
