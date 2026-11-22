# Skenario Uji Acara 29 (umpan balik dan escaping pada View)

Prasyarat: XAMPP berjalan, login sebagai admin (role admin). Tidak ada perubahan backend.

| ID | Skenario | Hasil yang diharapkan |
|----|----------|-----------------------|
| W-01 | Alur login, tambah (dengan foto), ubah, hapus oleh admin | Setiap tahap berumpan balik: tombol nonaktif saat proses, toast sukses |
| W-02 | Tambah data dengan nama `<b>Coba</b>` | Nama tampil sebagai teks `<b>Coba</b>`, tidak tebal (dashboard dan index.html) |
| W-03 | Tambah data dengan prodi `<img src=x onerror=alert(1)>` | Tidak ada kotak alert; tampil sebagai teks |
| W-04 | Hapus data lalu konfirmasi OK | Tombol "Menghapus..." sesaat; baris hilang; toast hijau |
| W-05 | Matikan Apache lalu klik Muat ulang | "Gagal memuat data: ..." memuat penyebab; Console mencatat satu kali percobaan ulang |
| W-06 | Klik Simpan pada form tambah saat server mati | Toast merah "Tidak dapat menghubungi server: ..."; tombol kembali normal |
| W-07 | Login dengan password salah | Pesan galat dari server; tombol kembali "Login" |
| W-08 | Nama data berfoto berisi tanda kutip ganda (`Budi" onmouseover="x`) | Nama tampil utuh; tidak ada atribut tambahan pada gambar |

Cara memperlambat permintaan untuk melihat label "Menyimpan...": DevTools > Network > Throttling > Slow 3G.

## Pengujian otomatis (Chromium + respons API tiruan)
19 pemeriksaan lulus: escaping nama/prodi/atribut alt, skrip tidak dieksekusi, retry tepat satu kali (2 permintaan bila gagal dua kali), status 500 tidak diulang, label dan status tombol (Simpan, Hapus, Login) selama proses dan sesudahnya.
