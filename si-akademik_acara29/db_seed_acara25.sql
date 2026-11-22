USE db_workshop;

-- Acara 25: akun admin.
-- POST /register selalu membuat role 'user', jadi role admin diberikan lewat SQL.
-- 1) Daftarkan akun lewat POST /register (contoh: username budi, password rahasia123).
-- 2) Jalankan perintah berikut. Akun budi juga dipakai Collection Postman ACARA25_TestCase.
UPDATE users SET role = 'admin' WHERE username = 'budi';

-- Pastikan hasilnya:
SELECT id, username, role FROM users;

-- Data contoh untuk dashboard (opsional, jalankan bila tabel mahasiswa kosong):
-- INSERT INTO mahasiswa (nim, nama, prodi) VALUES
--   ('220100001','Budi Santoso','Teknik Informatika'),
--   ('220100002','Siti Aminah','Sistem Informasi'),
--   ('220100003','Rina Putri','Teknik Informatika');
