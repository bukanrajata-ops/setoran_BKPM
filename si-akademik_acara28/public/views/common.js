// Berkas bersama untuk login.html, dashboard.html, form_tambah.html, dan form_edit.html (Acara 25-27).
// Alamat dasar API (folder public/ pada XAMPP). Ubah bila nama folder proyek berbeda.
// Acara 28: alamat domain hosting (Document Root = folder public/)
const BASE_URL = 'http://siakademikraja.iceiy.com';

const Sesi = {
    simpan(token, role) {
        localStorage.setItem('token', token);
        localStorage.setItem('role', role);
    },
    token() { return localStorage.getItem('token'); },
    role() { return localStorage.getItem('role'); },
    hapus() {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
    },
    // Membaca bagian payload JWT hanya untuk menampilkan/memeriksa masa berlaku di sisi klien.
    // Keabsahan token tetap ditentukan server (tanda tangan tidak dapat diperiksa di klien).
    payload() {
        try {
            const bagian = (this.token() || '').split('.')[1];
            const json = atob(bagian.replace(/-/g, '+').replace(/_/g, '/'));
            return JSON.parse(json);
        } catch (e) {
            return null;
        }
    },
    kedaluwarsa() {
        const p = this.payload();
        return !p || typeof p.exp !== 'number' || p.exp * 1000 <= Date.now();
    },
};

// Pemanggil API: menambahkan token dan mengubah 401 menjadi logout otomatis
async function panggilApi(path, { method = 'GET', body = null, auth = false } = {}) {
    const headers = {};
    const multipart = body instanceof FormData;
    // FormData: header Content-Type TIDAK diatur manual, browser menambahkan boundary sendiri (Acara 26)
    if (body !== null && !multipart) headers['Content-Type'] = 'application/json';
    if (auth) headers['Authorization'] = `Bearer ${Sesi.token()}`;

    const response = await fetch(`${BASE_URL}${path}`, {
        method,
        headers,
        body: body === null ? undefined : (multipart ? body : JSON.stringify(body)),
    });
    const hasil = await response.json();

    // Token kedaluwarsa (TTL 5 menit) atau tidak sah: bersihkan sesi lalu kembali ke login
    if (auth && response.status === 401) {
        Sesi.hapus();
        window.location.href = 'login.html?sesi=habis';
        throw new Error('Sesi berakhir');
    }
    return { status: response.status, ok: response.ok && hasil.success, hasil };
}

// ---- Acara 27 ----

// Alamat berkas foto di folder public/uploads (kosong bila mahasiswa belum punya foto)
function urlFoto(nama) {
    return nama ? `${BASE_URL}/uploads/${encodeURIComponent(nama)}` : '';
}

// Tugas mandiri 1: notifikasi visual (toast) untuk operasi Create/Update/Delete.
// Teks dipasang dengan textContent agar pesan dari server tidak diinterpretasikan sebagai HTML.
const Notifikasi = {
    KUNCI: 'notifikasi_berikut',
    tampil(teks, jenis = 'ok') {
        let wadah = document.querySelector('#notifikasi');
        if (!wadah) {
            wadah = document.createElement('div');
            wadah.id = 'notifikasi';
            wadah.setAttribute('role', 'status');
            wadah.setAttribute('aria-live', 'polite');
            document.body.appendChild(wadah);
        }
        const el = document.createElement('div');
        el.className = `toast ${jenis}`;
        el.textContent = teks;
        wadah.appendChild(el);
        setTimeout(() => el.remove(), 4000);
    },
    // Dipakai sebelum berpindah halaman (mis. setelah simpan): pesan tampil di halaman tujuan
    simpanUntukBerikut(teks, jenis = 'ok') {
        sessionStorage.setItem(this.KUNCI, JSON.stringify({ teks, jenis }));
    },
    tampilkanTersimpan() {
        const mentah = sessionStorage.getItem(this.KUNCI);
        if (!mentah) return;
        sessionStorage.removeItem(this.KUNCI);
        try {
            const { teks, jenis } = JSON.parse(mentah);
            this.tampil(teks, jenis);
        } catch (e) { /* abaikan data rusak */ }
    },
};

// Tugas mandiri 2: validasi sisi View. Lapisan tambahan; validasi yang menentukan tetap di server
// (MahasiswaController::validasiData dan prosesFoto).
const Validasi = {
    BATAS_FOTO: 2 * 1024 * 1024,
    foto(file) {
        if (!file) return '';
        if (!['image/jpeg', 'image/png'].includes(file.type)) return 'Berkas harus berupa gambar JPG/PNG yang valid';
        if (file.size > this.BATAS_FOTO) return 'Ukuran berkas maksimal 2MB';
        return '';
    },
    // Mengembalikan objek {field: pesan}; kosong bila semua field lolos
    mahasiswa({ nim, nama, prodi }, file = null) {
        const galat = {};
        if (!nim) galat.nim = 'NIM wajib diisi';
        else if (nim.length !== 9) galat.nim = 'NIM harus 9 digit';
        if (!nama) galat.nama = 'Nama wajib diisi';
        if (!prodi) galat.prodi = 'Program studi wajib diisi';
        const galatFoto = this.foto(file);
        if (galatFoto) galat.foto = galatFoto;
        return galat;
    },
};

// Pemeriksaan sesi untuk halaman admin (form tambah dan ubah)
function wajibAdmin() {
    if (!Sesi.token()) { window.location.href = 'login.html'; return false; }
    if (Sesi.kedaluwarsa()) { Sesi.hapus(); window.location.href = 'login.html?sesi=habis'; return false; }
    if (Sesi.role() !== 'admin') {
        Notifikasi.simpanUntukBerikut('Akses ditolak: hanya admin yang dapat mengelola data.', 'galat');
        window.location.href = 'dashboard.html';
        return false;
    }
    return true;
}

// Mengisi pesan galat per field (.galat-field[data-field]) pada sebuah form
function tampilkanGalatField(form, errors = {}) {
    form.querySelectorAll('.galat-field').forEach((el) => { el.textContent = ''; });
    Object.entries(errors).forEach(([field, pesan]) => {
        const el = form.querySelector(`.galat-field[data-field="${field}"]`);
        if (el) el.textContent = pesan;
    });
}
