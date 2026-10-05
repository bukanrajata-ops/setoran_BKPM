CREATE DATABASE IF NOT EXISTS si_akademik;
USE si_akademik;

-- Acara 8 menggunakan tabel yang sudah dibuat pada Acara 7.
-- Jika database Acara 7 sudah ada, tidak perlu menjalankan CREATE ulang.

ALTER TABLE mahasiswa ADD COLUMN IF NOT EXISTS status ENUM('aktif','cuti','lulus') NOT NULL DEFAULT 'aktif';

-- Data awal (gunakan hanya bila tabel masih kosong)
INSERT INTO prodi (kode,nama) SELECT 'TI','Teknik Informatika' WHERE NOT EXISTS (SELECT 1 FROM prodi WHERE kode='TI');
INSERT INTO prodi (kode,nama) SELECT 'SI','Sistem Informasi' WHERE NOT EXISTS (SELECT 1 FROM prodi WHERE kode='SI');
INSERT INTO prodi (kode,nama) SELECT 'TK','Teknik Komputer' WHERE NOT EXISTS (SELECT 1 FROM prodi WHERE kode='TK');

-- Contoh pencarian yang dipakai Tugas Mandiri:
-- SELECT ... FROM mahasiswa WHERE nim LIKE :keyword OR nama LIKE :keyword;

SELECT
    m.*, p.nama AS prodi_nama
FROM
    mahasiswa m
JOIN
    prodi p ON m.prodi_id = p.id
ORDER BY m.nim;