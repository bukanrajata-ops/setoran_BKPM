// Acara 25-29: dashboard admin (ringkasan, tabel, logout, RBAC di sisi tampilan).
// Acara 27: tambah dan ubah dipindah ke form_tambah.html / form_edit.html; hapus dari tabel dengan notifikasi.
if (!Sesi.token()) {
    window.location.href = 'login.html';              // belum login
} else if (Sesi.kedaluwarsa()) {
    Sesi.hapus();
    window.location.href = 'login.html?sesi=habis';   // pernah login, masa berlaku habis
}

const adalahAdmin = Sesi.role() === 'admin';

const tbody = document.querySelector('#tabel-mahasiswa tbody');
const statusEl = document.querySelector('#status');
const totalEl = document.querySelector('#total');
const sisaSesiEl = document.querySelector('#sisa-sesi');

document.querySelector('#peran').textContent = Sesi.role() || '-';

// Tugas mandiri 2: role user tidak melihat tombol Tambah/Ubah/Hapus.
// Ini hanya kenyamanan tampilan; penegakan sebenarnya oleh Auth::requireAdmin() di server.
if (!adalahAdmin) {
    document.querySelectorAll('.aksi-admin').forEach((el) => { el.hidden = true; });
}

function tampilkanStatus(teks, jenis = '') {
    statusEl.textContent = teks;
    statusEl.className = 'status ' + jenis;
    statusEl.hidden = teks === '';
}

// Tugas mandiri 1: Logout menghapus token dari localStorage lalu kembali ke login.
// JWT bersifat stateless, jadi token lama tetap sah di server sampai kedaluwarsa.
function logout() {
    Sesi.hapus();
    window.location.href = 'login.html';
}
document.querySelector('#tombol-logout').addEventListener('click', logout);

// Hitung mundur masa berlaku token (TTL 5 menit); logout otomatis saat habis
function perbaruiSisaSesi() {
    if (!Sesi.token()) return; // belum login: guard di atas sudah mengarahkan ke login.html
    const p = Sesi.payload();
    const sisa = p ? Math.floor(p.exp - Date.now() / 1000) : 0;
    if (sisa <= 0) {
        Sesi.hapus();
        window.location.href = 'login.html?sesi=habis';
        return;
    }
    const menit = String(Math.floor(sisa / 60)).padStart(2, '0');
    const detik = String(sisa % 60).padStart(2, '0');
    sisaSesiEl.textContent = `${menit}:${detik}`;
}
perbaruiSisaSesi();
setInterval(perbaruiSisaSesi, 1000);

// Acara 29 - Tugas mandiri 1: seluruh data dinamis disisipkan lewat escapeHTML() (nim, nama, prodi, foto, id)
function barisHTML(mhs) {
    const foto = mhs.foto
        ? `<img class="foto-kecil" src="${escapeHTML(urlFoto(mhs.foto))}" alt="Foto ${escapeHTML(mhs.nama)}" loading="lazy">`
        : '<div class="tanpa-foto">-</div>';
    const aksi = adalahAdmin
        ? `<td class="kolom-aksi aksi-admin">
             <a class="tombol kecil" href="form_edit.html?id=${encodeURIComponent(mhs.id)}">Ubah</a>
             <button type="button" class="kecil bahaya" data-hapus="${escapeHTML(mhs.id)}">Hapus</button>
           </td>`
        : '';
    return `<tr><td>${foto}</td><td>${escapeHTML(mhs.nim)}</td><td>${escapeHTML(mhs.nama)}</td><td>${escapeHTML(mhs.prodi)}</td>${aksi}</tr>`;
}

function renderTabel(data) {
    // innerHTML mengganti seluruh isi: dua pemanggilan bersamaan tidak menggandakan baris
    tbody.innerHTML = data.map(barisHTML).join('');
}

// Event delegation: satu pendengar untuk seluruh tombol Hapus (tombol dibuat ulang setiap render)
let dataTerakhir = [];
tbody.addEventListener('click', (e) => {
    const tombol = e.target.closest('button[data-hapus]');
    if (!tombol) return;
    const mhs = dataTerakhir.find((m) => String(m.id) === tombol.dataset.hapus);
    if (mhs) hapusMahasiswa(mhs, tombol);
});

// Langkah 5: jumlah total mahasiswa diambil dari GET /mahasiswa (MahasiswaController::index)
async function muatDataMahasiswa() {
    tbody.replaceChildren();
    tampilkanStatus('Memuat data...', 'info');
    try {
        const { ok, status, hasil } = await panggilApi('/mahasiswa');
        if (!ok) throw new Error(hasil.message || `HTTP ${status}`);
        totalEl.textContent = hasil.data.length;
        if (hasil.data.length === 0) {
            tbody.replaceChildren();
            tampilkanStatus('Belum ada data', 'kosong');
            return;
        }
        dataTerakhir = hasil.data;
        renderTabel(hasil.data);
        tampilkanStatus('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        totalEl.textContent = '-';
        if (error.message === 'Sesi berakhir') return;          // sudah dialihkan ke halaman login
        tampilkanStatus(`Gagal memuat data: ${error.message}`, 'galat');
    }
}

async function hapusMahasiswa(mhs, tombol) {
    if (!confirm(`Hapus data ${mhs.nama} (${mhs.nim})?`)) return;
    // Acara 29 - Langkah 3: try-catch-finally; tombol dinonaktifkan selama permintaan berlangsung
    tombol.disabled = true;
    tombol.textContent = 'Menghapus...';
    try {
        const { ok, status, hasil } = await panggilApi(`/mahasiswa?id=${mhs.id}`, { method: 'DELETE', auth: true });
        if (!ok) {
            Notifikasi.tampil(status === 403 ? 'Akses ditolak: hanya admin yang dapat menghapus data.' : `Gagal menghapus data: ${hasil.message}`, 'galat');
            return;
        }
        Notifikasi.tampil(`Data ${mhs.nama} berhasil dihapus.`);
        await muatDataMahasiswa();           // tabel diperbarui tanpa reload penuh
    } catch (error) {
        if (error.message !== 'Sesi berakhir') {
            console.error('Gagal menghapus:', error);
            Notifikasi.tampil(`Tidak dapat menghubungi server: ${error.message}`, 'galat');
        }
    } finally {
        if (tombol.isConnected) { tombol.disabled = false; tombol.textContent = 'Hapus'; }
    }
}

document.querySelector('#tombol-muat').addEventListener('click', muatDataMahasiswa);
document.addEventListener('DOMContentLoaded', () => {
    Notifikasi.tampilkanTersimpan();         // pesan hasil tambah/ubah dari halaman form
    muatDataMahasiswa();
});
