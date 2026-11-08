// Acara 25-27: dashboard admin (ringkasan, tabel, logout, RBAC di sisi tampilan).
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

function buatSel(teks) {
    const td = document.createElement('td');
    td.textContent = teks; // textContent: data tidak diinterpretasikan sebagai HTML
    return td;
}

function buatSelFoto(mhs) {
    const td = document.createElement('td');
    if (mhs.foto) {
        const img = document.createElement('img');
        img.className = 'foto-kecil';
        img.src = urlFoto(mhs.foto);
        img.alt = `Foto ${mhs.nama}`;        // properti DOM, bukan innerHTML
        img.loading = 'lazy';
        td.appendChild(img);
    } else {
        const kosong = document.createElement('div');
        kosong.className = 'tanpa-foto';
        kosong.textContent = '-';
        td.appendChild(kosong);
    }
    return td;
}

function buatBaris(mhs) {
    const tr = document.createElement('tr');
    tr.appendChild(buatSelFoto(mhs));
    [mhs.nim, mhs.nama, mhs.prodi].forEach((nilai) => tr.appendChild(buatSel(nilai)));
    if (adalahAdmin) {
        const td = document.createElement('td');
        td.className = 'kolom-aksi aksi-admin';
        const ubah = document.createElement('a');
        ubah.className = 'tombol kecil';
        ubah.textContent = 'Ubah';
        ubah.href = `form_edit.html?id=${encodeURIComponent(mhs.id)}`;   // Langkah 2: id lewat query string
        const hapus = document.createElement('button');
        hapus.type = 'button';
        hapus.className = 'kecil bahaya';
        hapus.textContent = 'Hapus';
        hapus.addEventListener('click', () => hapusMahasiswa(mhs));
        td.append(ubah, ' ', hapus);
        tr.appendChild(td);
    }
    return tr;
}

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
        // replaceChildren() saat render: dua pemanggilan bersamaan tidak menggandakan baris
        tbody.replaceChildren(...hasil.data.map(buatBaris));
        tampilkanStatus('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        totalEl.textContent = '-';
        tampilkanStatus('Gagal memuat data. Pastikan server berjalan.', 'galat');
    }
}

async function hapusMahasiswa(mhs) {
    if (!confirm(`Hapus data ${mhs.nama} (${mhs.nim})?`)) return;
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
            Notifikasi.tampil('Tidak dapat menghubungi server.', 'galat');
        }
    }
}

document.querySelector('#tombol-muat').addEventListener('click', muatDataMahasiswa);
document.addEventListener('DOMContentLoaded', () => {
    Notifikasi.tampilkanTersimpan();         // pesan hasil tambah/ubah dari halaman form
    muatDataMahasiswa();
});
