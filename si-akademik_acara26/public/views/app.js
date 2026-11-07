// Alamat dasar API (folder public/ pada XAMPP). Ubah bila nama folder proyek berbeda.
const BASE_URL = 'http://localhost/setoran_BKPM-1/si-akademik_acara26/public';

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const statusEl = document.querySelector('#status');

// Menampilkan atau menyembunyikan pesan status di bawah tabel
function tampilkanStatus(teks, jenis = '') {
    statusEl.textContent = teks;
    statusEl.className = 'status ' + jenis;
    statusEl.hidden = teks === '';
}

// Baris dibuat dengan textContent agar isi data tidak diinterpretasikan sebagai HTML
function buatBaris(mhs) {
    const tr = document.createElement('tr');
    [mhs.nim, mhs.nama, mhs.prodi].forEach((nilai) => {
        const td = document.createElement('td');
        td.textContent = nilai;
        tr.appendChild(td);
    });
    return tr;
}

async function muatDataMahasiswa() {
    tbody.replaceChildren();
    tampilkanStatus('Memuat data...', 'info');            // Tugas mandiri 1: indikator memuat
    try {
        const response = await fetch(`${BASE_URL}/mahasiswa`);
        const hasil = await response.json();
        if (!response.ok || !hasil.success) {
            throw new Error(hasil.message || `HTTP ${response.status}`);
        }
        if (hasil.data.length === 0) {                     // Tugas mandiri 2: data kosong
            tampilkanStatus('Belum ada data', 'kosong');
            return;
        }
        hasil.data.forEach((mhs) => tbody.appendChild(buatBaris(mhs)));
        tampilkanStatus('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanStatus('Gagal memuat data. Pastikan server berjalan.', 'galat');
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
document.querySelector('#tombol-muat').addEventListener('click', muatDataMahasiswa);
