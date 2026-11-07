# Test Case Acara 27 - Alur CRUD dari Sisi View

Prasyarat: login sebagai admin (badge ADMIN), server Apache dan MySQL berjalan.

| ID | Skenario | Langkah | Hasil yang diharapkan |
|----|----------|---------|-----------------------|
| V-01 | Tambah data valid dengan foto | Dashboard > Tambah > isi NIM 9 karakter, nama, prodi, pilih foto JPG/PNG <= 2 MB > Simpan | Pindah ke dashboard; toast hijau "berhasil ditambahkan"; baris baru dengan foto muncul |
| V-02 | Tambah tanpa foto | Sama seperti V-01 tanpa foto | Data tersimpan; kolom foto bertanda "-" |
| V-03 | Validasi View: field kosong | Tambah > klik Simpan tanpa isi | Pesan "wajib diisi" di tiap field; toast merah; tidak ada request ke server |
| V-04 | Validasi View: NIM bukan 9 karakter | NIM 5 karakter > Simpan | Pesan "NIM harus 9 digit"; tidak ada request ke server |
| V-05 | Validasi View: foto > 2 MB / bukan gambar | Pilih berkas tidak sesuai | Pesan galat pada field foto; Simpan ditolak |
| V-06 | NIM duplikat (validasi server) | Tambah dengan NIM yang sudah ada | Toast merah "Gagal menambah data"; pesan 409 pada field NIM |
| V-07 | Ubah data tanpa ganti foto | Dashboard > Ubah > ubah nama > Simpan | Toast hijau "berhasil diperbarui"; nama berubah; foto lama tetap |
| V-08 | Ubah data dengan foto baru | Ubah > pilih foto lain > Simpan | Foto berganti; berkas lama terhapus dari public/uploads (unlink) |
| V-09 | Ubah id tidak ada | Buka form_edit.html?id=9999 | Pesan "Data tidak ditemukan"; form tidak tampil |
| V-10 | Hapus data | Dashboard > Hapus > OK | Toast hijau "berhasil dihapus"; baris hilang tanpa reload penuh; baris dan berkas foto terhapus |
| V-11 | Batal hapus | Hapus > Cancel | Tidak ada perubahan |
| V-12 | Role user | Login sebagai user; buka form_tambah.html | Dialihkan ke dashboard dengan toast "Akses ditolak"; tombol aksi tidak tampil |
| V-13 | Sesi habis | Tunggu token kedaluwarsa (5 menit) lalu simpan | Kembali ke login.html?sesi=habis |
