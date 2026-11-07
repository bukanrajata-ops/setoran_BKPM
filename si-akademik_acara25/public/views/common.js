// Berkas bersama untuk login.html dan dashboard.html (Acara 25).
// Alamat dasar API (folder public/ pada XAMPP). Ubah bila nama folder proyek berbeda.
const BASE_URL = 'http://localhost/setoran_BKPM-1/si-akademik_acara25/public';

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
    if (body !== null) headers['Content-Type'] = 'application/json';
    if (auth) headers['Authorization'] = `Bearer ${Sesi.token()}`;

    const response = await fetch(`${BASE_URL}${path}`, {
        method,
        headers,
        body: body !== null ? JSON.stringify(body) : undefined,
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
