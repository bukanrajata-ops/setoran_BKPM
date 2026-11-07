USE db_workshop;

-- Acara 26, Langkah 1: kolom foto pada tabel mahasiswa.

-- Jalankan SEKALI saja (menjalankan dua kali menghasilkan error "Duplicate column name").
ALTER TABLE mahasiswa ADD COLUMN foto VARCHAR(255) DEFAULT NULL AFTER prodi;

DESCRIBE mahasiswa;
