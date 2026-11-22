# Simulasi Pemulihan Bencana (Acara 30, Tugas Praktik Mandiri 1)

Tujuan: menghapus satu tabel pada basis data **percobaan**, lalu memulihkannya dari berkas backup Acara 26.
Database utama (`db_workshop` atau database hosting) **tidak disentuh**.

## Persiapan
1. Ambil backup terbaru dari `db_workshop` (salah satu):
   - phpMyAdmin: pilih `db_workshop` > Export > Quick > SQL > Export (hasil `db_workshop.sql`), atau
   - `backup\backup_db.bat` (hasil di `backup\hasil\db_workshop_<tanggal>.sql`).
2. Buat database percobaan `db_workshop_restore` (collation `utf8mb4_general_ci`) dan impor backup tadi ke dalamnya.
3. Catat jumlah baris tiap tabel sebelum bencana:

       SELECT 'mahasiswa', COUNT(*) FROM mahasiswa UNION SELECT 'buku', COUNT(*) FROM buku UNION SELECT 'users', COUNT(*) FROM users;

## Bencana (pada database percobaan saja)
Pilih tabel `buku` (tidak dirujuk foreign key oleh tabel lain):

    USE db_workshop_restore;
    DROP TABLE buku;
    SHOW TABLES;          -- buku tidak ada lagi
    SELECT * FROM buku;   -- ERROR 1146: Table ... doesn't exist

## Pemulihan
Cara paling aman (database percobaan dibuat ulang lalu backup diimpor penuh):
- phpMyAdmin: hapus database `db_workshop_restore`, buat kembali kosong, pilih tab **Import**, pilih berkas backup, klik **Import**; atau
- Windows: `backup\pulihkan_db.bat backup\hasil\db_workshop_<tanggal>.sql db_workshop_restore`
  (skrip menolak bila tujuannya `db_workshop`).

## Verifikasi
1. `SHOW TABLES;` memuat `buku`, `mahasiswa`, `users`.
2. Jalankan lagi query hitung baris: angkanya harus sama dengan sebelum bencana.
3. `SELECT judul, penulis, tahun FROM buku;` menampilkan data semula.
4. Foto tidak ikut dalam dump database (hanya nama berkas). Salin juga folder `public/uploads/` (lihat `backup_db.bat`).

## Catatan
- Hasil verifikasi prosedur ini di sandbox (MariaDB): sebelum bencana mahasiswa 4, buku 2, users 1; setelah `DROP TABLE buku` tabel hilang; setelah pemulihan jumlah baris kembali sama.
- Backup yang dibuat phpMyAdmin Quick tidak memuat `DROP TABLE IF EXISTS`, sehingga mengimpornya ke database yang masih berisi tabel menghasilkan galat "table already exists". Karena itu database percobaan dibuat ulang sebelum impor.
- Kehilangan data sejak backup terakhir tidak dapat dipulihkan; jadwal backup ada di `backup/README_BACKUP.md`.
