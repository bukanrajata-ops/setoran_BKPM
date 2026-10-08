-- Acara 15 - Database untuk API Data Mahasiswa
-- Skema ini disusun dari kode project (MahasiswaRepository & ProdiRepository).
-- Aman dijalankan pada database yang sudah ada: tabel dibuat hanya jika belum
-- ada, dan data contoh dimasukkan hanya jika NIM-nya belum terdaftar.

CREATE DATABASE IF NOT EXISTS si_akademik
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE si_akademik;

CREATE TABLE IF NOT EXISTS prodi (
    id   INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10)  NOT NULL,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS mahasiswa (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    nim      VARCHAR(20)  NOT NULL UNIQUE,
    nama     VARCHAR(100) NOT NULL,
    email    VARCHAR(100) NOT NULL,
    prodi_id INT          NOT NULL,
    angkatan INT          NOT NULL,
    status   VARCHAR(10)  NOT NULL DEFAULT 'aktif'
);

-- Prodi contoh (hanya jika tabel prodi masih kosong)
INSERT INTO prodi (kode, nama)
SELECT 'TIF', 'Teknik Informatika' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM prodi);

-- Data contoh dari modul (prodi_id memakai prodi pertama yang ada)
INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
SELECT '23001', 'Budi', 'budi@gmail.com', (SELECT MIN(id) FROM prodi), 2023, 'aktif' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM mahasiswa WHERE nim = '23001');

INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
SELECT '23002', 'Siti', 'siti@gmail.com', (SELECT MIN(id) FROM prodi), 2023, 'aktif' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM mahasiswa WHERE nim = '23002');

INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
SELECT '23003', 'Andi', 'andi@gmail.com', (SELECT MIN(id) FROM prodi), 2023, 'aktif' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM mahasiswa WHERE nim = '23003');
