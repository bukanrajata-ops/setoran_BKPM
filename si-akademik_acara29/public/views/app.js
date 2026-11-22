// Alamat dasar API (folder public/ pada XAMPP). Ubah bila nama folder proyek berbeda.
const BASE_URL = 'http://localhost/setoran_BKPM-1/si-akademik_acara29/public';

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const statusEl = document.querySelector('#status');

// Menampilkan atau menyembunyikan pesan status di bawah tabel
function tampilkanStatus(teks, jenis = '') {
    statusEl.textContent = teks;
    statusEl.className = 'status ' + jenis;
    statusEl.hidden = teks === '';
}

// Acara 29 - Langkah 2: escaping output sebelum data masuk ke innerHTML
function escapeHTML(teks) {
    const div = document.createElement('div');
    div.textContent = teks === null || teks === undefined ? '' : String(teks);
    return div.innerHTML.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

// Acara 29 - Tugas mandiri 2: retry otomatis satu kali bila fetch gagal karena jaringan
async function fetchDenganRetry(url, opsi, jeda = 600) {
    try {
        return await fetch(url, opsi);
    } catch (error) {
        console.warn('Permintaan gagal, mencoba ulang satu kali:', error);
        await new Promise((selesai) => setTimeout(selesai, jeda));
        return fetch(url, opsi);
    }
}

function renderTabel(data) {
    tbody.innerHTML = data.map((mhs) =>
        `<tr><td>${escapeHTML(mhs.nim)}</td><td>${escapeHTML(mhs.nama)}</td><td>${escapeHTML(mhs.prodi)}</td></tr>`
    ).join('');
}

// Acara 29 - Langkah 1: indikator loading dan pesan galat yang menyebut penyebabnya
async function muatDataMahasiswa() {
    tbody.replaceChildren();
    tampilkanStatus('Memuat data...', 'info');
    try {
        const response = await fetchDenganRetry(`${BASE_URL}/mahasiswa`);
        if (!response.ok) throw new Error('Server merespons dengan status ' + response.status);
        const hasil = await response.json();
        if (!hasil.success) throw new Error(hasil.message || 'Respons tidak valid');
        if (hasil.data.length === 0) {
            tampilkanStatus('Belum ada data', 'kosong');
            return;
        }
        renderTabel(hasil.data);
        tampilkanStatus('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanStatus(`Gagal memuat data: ${error.message}`, 'galat');
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
document.querySelector('#tombol-muat').addEventListener('click', muatDataMahasiswa);
