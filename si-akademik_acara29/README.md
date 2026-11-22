# SI Akademik - Acara 29 (Integrasi Backend-Frontend: Umpan Balik dan Keamanan Output)

Backend (Model, Controller, Router, Auth) dan basis data TIDAK berubah dari Acara 27. Acara 29 hanya menyempurnakan View.
Ubah BASE_URL di public/views/app.js dan common.js bila nama folder proyek berbeda (default: .../setoran_BKPM-1/si-akademik_acara29/public).

Perubahan dari Acara 27:
- UBAH  public/views/app.js          -> Langkah 1-2: indikator memuat, pesan galat dengan penyebab, escapeHTML(), renderTabel()
- UBAH  public/views/common.js       -> escapeHTML(), fetchDenganRetry() (tugas mandiri 2); panggilApi() memakai retry
- UBAH  public/views/dashboard.js    -> render tabel lewat escapeHTML() (tugas mandiri 1); tombol Hapus nonaktif selama proses (Langkah 3)
- UBAH  public/views/form_tambah.js, form_edit.js, login.js -> Langkah 3: try-catch-finally, tombol nonaktif/berlabel proses, galat dengan penyebab
- Tugas mandiri 1: seluruh data dinamis (nim, nama, prodi, foto, id) di-escape; pesan memakai textContent
- Tugas mandiri 2: retry otomatis satu kali bila fetch gagal karena jaringan (respons HTTP 4xx/5xx tidak diulang)
- Skenario uji: docs/TEST_CASE_ACARA29.md

Langkah menjalankan:
1. Letakkan folder di htdocs (setoran_BKPM-1/si-akademik_acara29). Ubah BASE_URL di public/views/common.js dan app.js bila nama folder berbeda.
2. Database: pakai db_workshop yang sama dengan Acara 26 (kolom foto sudah ada). Bila belum, jalankan db_acara26.sql sekali.
3. Akun admin seperti sebelumnya (UPDATE users SET role='admin' WHERE username='budi';).
4. Buka http://localhost/setoran_BKPM-1/si-akademik_acara29/public/views/login.html
