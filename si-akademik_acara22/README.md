# SI Akademik - Acara 22 (Dokumentasi OpenAPI / Swagger)

Acara 22 tidak mengubah kode program. Yang ditambahkan:
- BARU  docs/openapi.yaml  -> dokumentasi OpenAPI 3.0 (path /register, /login, /mahasiswa, /buku; bearerAuth)
- UBAH  postman/Local.postman_environment.json -> base_url ke folder si-akademik_acara22
- docs/API_DOCUMENTATION.md (Acara 21) tetap dipertahankan sebagai dokumentasi Markdown

Visualisasi:
1. Buka https://editor.swagger.io
2. Tempelkan seluruh isi docs/openapi.yaml pada panel kiri, amati pratinjau pada panel kanan.
3. Simpan tangkapan layar ke docs/ (misalnya docs/swagger-preview.png) sebagai lampiran.

Validasi (sudah dijalankan): openapi-spec-validator = valid; Redocly CLI = valid (4 peringatan non-kritis).
