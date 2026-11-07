# SI Akademik - Acara 24 (Integrasi API dengan frontend)

Perubahan dari Acara 23:
- UBAH  public/index.php  -> header CORS (Allow-Origin/Methods/Headers) + jawaban 204 untuk preflight OPTIONS
- BARU  public/views/index.html -> halaman tabel data mahasiswa
- BARU  public/views/app.js     -> Fetch API (async/await), indikator "Memuat data...", pesan "Belum ada data", penanganan galat
- UBAH  docs/API_DOCUMENTATION.md dan docs/openapi.yaml -> keterangan CORS
- UBAH  postman/Local.postman_environment.json -> base_url ke folder si-akademik_acara24

Membuka halaman (berkas statis, tidak melewati Router):
  http://localhost/setoran_BKPM-1/si-akademik_acara24/public/views/index.html

Ubah BASE_URL pada public/views/app.js bila nama folder proyek berbeda.
Database.php: gunakan versi milikmu (port 3307) di app/config/.

Data contoh (phpMyAdmin > db_workshop > SQL):
  INSERT INTO mahasiswa (nim, nama, prodi) VALUES
  ('220100001','Budi Santoso','Teknik Informatika'),
  ('220100002','Siti Aminah','Sistem Informasi'),
  ('220100003','Rina Putri','Teknik Informatika');
