# Test Case Acara 28 - Pasca-Deployment

| ID | Skenario | Hasil yang diharapkan |
|----|----------|-----------------------|
| P-01 | Buka `https://DOMAIN/views/login.html` | Halaman login tampil |
| P-02 | GET `{{base_url}}/mahasiswa` | 200, data tampil dari MahasiswaController |
| P-03 | Login admin lalu tambah, ubah, hapus data dari View | Operasi berhasil, tabel diperbarui |
| P-04 | Jalankan koleksi Postman Acara 23/26 dengan environment Production | Pass seperti di lokal |
| P-05 | Buka `https://DOMAIN/app/config/Database.php` | 404 atau 403 |
