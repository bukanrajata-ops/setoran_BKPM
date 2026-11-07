# SI Akademik - Acara 23 (Manual testing dan Blackbox testing)

Perubahan dari Acara 22:
- BARU  docs/TEST_CASE.md  -> 25 test case (Equivalence Partitioning), hasil awal dan retest
- BARU  postman/ACARA23_TestCase.postman_collection.json -> test case siap dijalankan via Collection Runner
- UBAH  app/controllers/MahasiswaController.php -> perbaikan update() dan destroy() (400 id kosong, 404 data tidak ada, 400 field kosong)
- UBAH  docs/API_DOCUMENTATION.md dan docs/openapi.yaml -> disesuaikan dengan perilaku baru
- UBAH  postman/Local.postman_environment.json -> base_url ke folder si-akademik_acara23

Menjalankan test case:
1. Import postman/ACARA23_TestCase.postman_collection.json dan Local.postman_environment.json, aktifkan Local.
2. Klik collection > Run > Functional > Run manually, urutan dibiarkan apa adanya.
3. Hasil Pass/Fail muncul pada tab Test Results. Token berlaku 5 menit, jalankan sekali jalan.

Untuk melihat hasil SEBELUM perbaikan, jalankan Collection yang sama pada folder proyek Acara 22 (kode lama): 5 test case Fail (TC-15, 19, 21, 23, 24).
Setelah perbaikan (folder ini): 25/25 Pass.
