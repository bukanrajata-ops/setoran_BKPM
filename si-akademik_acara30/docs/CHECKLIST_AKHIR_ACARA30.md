# Checklist Akhir dan Serah Terima (Acara 30, Langkah 5)

Centang setelah benar-benar dilakukan pada hosting produksi (https://siakademikraja.iceiy.com).

## A. Penguatan lingkungan produksi
- [ ] SSL aktif untuk domain (cPanel: SSL/TLS Status > Run AutoSSL; panel lain: menu sertifikat SSL gratis). Alamat `https://` menampilkan gembok.
- [ ] `public/.htaccess` terbaru diunggah ke root web (redirect HTTP ke HTTPS aktif).
- [ ] `display_errors` Off dan `log_errors` On. Bila MultiPHP INI Editor tersedia, isi di sana; bila tidak, `app/config/config.php` (APP_ENV = production) sudah mengaturnya lewat kode.
- [ ] Folder `app/` diunggah beserta `app/.htaccess`; folder `app/logs/` dapat ditulis server.
- [ ] `app/config/credentials.php` dibuat di server (salin dari `credentials.example.php`), password hosting diisi, berkas **tidak** ikut ke Git.
- [ ] `Auth::SECRET` memakai kunci acak panjang (dicek sejak Acara 28).
- [ ] `BASE_URL` di `public/views/app.js` dan `common.js` berawalan `https://`.

## B. Pengujian produksi
- [ ] Seluruh test case Acara 23 dijalankan ulang dengan environment Postman **Production** (base_url `https://...`).
- [ ] Skenario di `docs/TEST_CASE_ACARA30.md` dijalankan; hasil dan tangkapan layar dicatat.
- [ ] `https://DOMAIN/app/config/Database.php` dan `.../credentials.php` menghasilkan 403/404.

## C. Dokumentasi
- [x] `docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml`: base URL menunjuk `https://siakademikraja.iceiy.com`.
- [x] `postman/Production.postman_environment.json`: base_url `https://...`.
- [ ] Laporan akhir (Acara 17 sampai 30) dengan diagram struktur `app/` dan `public/` disusun.

## D. Pemulihan dan presentasi
- [ ] Simulasi disaster recovery dijalankan (`docs/DISASTER_RECOVERY.md`) dan buktinya difoto.
- [ ] Bahan presentasi: arsitektur MVC, alur autentikasi (login > token > header Authorization), bukti pengujian.
