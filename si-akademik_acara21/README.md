# SI Akademik - Acara 21 (Dokumentasi endpoint)

Acara 21 tidak mengubah kode program. Yang ditambahkan:
- BARU  docs/API_DOCUMENTATION.md  -> dokumentasi seluruh endpoint (ringkasan, peta Router, autentikasi, contoh response)
- BARU  postman/ACARA21_Collection.postman_collection.json -> deskripsi Controller::method + Examples pada tiap request
- UBAH  postman/Local.postman_environment.json -> base_url ke folder si-akademik_acara21

Postman:
1. Import ACARA21_Collection + Local.postman_environment.json, aktifkan Environment Local.
2. Jalankan "POST Login" dulu; token tersimpan otomatis ke {{token}} dan berlaku 5 menit.
3. Collection > tab Overview > View complete documentation.

Catatan perilaku dan keterbatasan API ada di docs/API_DOCUMENTATION.md (bagian 8).
