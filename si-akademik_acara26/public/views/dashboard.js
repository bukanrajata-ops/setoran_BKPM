// Acara 25: dashboard admin (ringkasan, tabel, CRUD, logout, RBAC di sisi tampilan)
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
const dialog = document.querySelector('#dialog-form');
const form = document.querySelector('#form-mahasiswa');
const pesanForm = document.querySelector('#pesan-form');
let idDiedit = null; // null = mode tambah, angka = mode ubah
let mhsDiedit = null;

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

function tampilkanPesanForm(teks, jenis = '') {
    pesanForm.textContent = teks;
    pesanForm.className = 'pesan ' + jenis;
    pesanForm.hidden = teks === '';
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

// Alamat berkas foto di folder public/uploads (null bila mahasiswa belum punya foto)
function urlFoto(nama) {
    return nama ? `${BASE_URL}/uploads/${encodeURIComponent(nama)}` : '';
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
        const ubah = document.createElement('button');
        ubah.type = 'button';
        ubah.className = 'kecil';
        ubah.textContent = 'Ubah';
        ubah.addEventListener('click', () => bukaForm(mhs));
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

// ---- Form tambah / ubah ----
function bersihkanGalatField() {
    form.querySelectorAll('.galat-field').forEach((el) => { el.textContent = ''; });
    tampilkanPesanForm('');
}

const inputFoto = document.querySelector('#foto');
const pratinjau = document.querySelector('#pratinjau-foto');
const BATAS_FOTO = 2 * 1024 * 1024;

function tampilkanPratinjau(src) {
    pratinjau.hidden = !src;
    if (src) pratinjau.src = src; else pratinjau.removeAttribute('src');
}

// Pemeriksaan awal di klien hanya untuk kenyamanan; validasi yang menentukan tetap di server
function periksaFoto(file) {
    if (!file) return '';
    if (!['image/jpeg', 'image/png'].includes(file.type)) return 'Berkas harus berupa gambar JPG/PNG yang valid';
    if (file.size > BATAS_FOTO) return 'Ukuran berkas maksimal 2MB';
    return '';
}

inputFoto.addEventListener('change', () => {
    const file = inputFoto.files[0];
    const galat = periksaFoto(file);
    form.querySelector('.galat-field[data-field="foto"]').textContent = galat;
    if (!file || galat) { tampilkanPratinjau(mhsDiedit ? urlFoto(mhsDiedit.foto) : ''); return; }
    tampilkanPratinjau(URL.createObjectURL(file));
});

function bukaForm(mhs = null) {
    idDiedit = mhs ? mhs.id : null;
    mhsDiedit = mhs;
    inputFoto.value = '';
    tampilkanPratinjau(mhs ? urlFoto(mhs.foto) : '');
    document.querySelector('#judul-form').textContent = mhs ? 'Ubah Mahasiswa' : 'Tambah Mahasiswa';
    form.elements.nim.value = mhs ? mhs.nim : '';
    form.elements.nama.value = mhs ? mhs.nama : '';
    form.elements.prodi.value = mhs ? mhs.prodi : '';
    bersihkanGalatField();
    dialog.showModal();
    form.elements.nim.focus();
}

document.querySelector('#tombol-tambah').addEventListener('click', () => bukaForm());
document.querySelector('#tombol-batal').addEventListener('click', () => dialog.close());

// Pesan kesalahan per field dari respons API (400/422/409)
function tampilkanGalatField(errors = {}) {
    Object.entries(errors).forEach(([field, pesan]) => {
        const el = form.querySelector(`.galat-field[data-field="${field}"]`);
        if (el) el.textContent = pesan;
    });
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    bersihkanGalatField();
    const file = inputFoto.files[0];
    const galatFoto = periksaFoto(file);
    if (galatFoto) {
        form.querySelector('.galat-field[data-field="foto"]').textContent = galatFoto;
        return;
    }
    const tombolSimpan = document.querySelector('#tombol-simpan');
    tombolSimpan.disabled = true;
    try {
        const edit = idDiedit !== null;
        // multipart/form-data: foto hanya dilampirkan bila dipilih (append(undefined) akan mengirim teks "undefined")
        const formData = new FormData();
        formData.append('nim', form.elements.nim.value.trim());
        formData.append('nama', form.elements.nama.value.trim());
        formData.append('prodi', form.elements.prodi.value.trim());
        if (file) formData.append('foto', file);
        // PHP tidak mengisi $_FILES pada PUT, jadi pembaruan memakai POST dengan _method=PUT
        const { ok, status, hasil } = await panggilApi(edit ? `/mahasiswa?id=${idDiedit}&_method=PUT` : '/mahasiswa', {
            method: 'POST',
            auth: true,
            body: formData,
        });
        if (ok) {
            dialog.close();
            await muatDataMahasiswa();
            return;
        }
        // 403: token sah tetapi bukan admin; 400/422/409: kesalahan validasi
        tampilkanPesanForm(status === 403 ? 'Akses ditolak: hanya admin yang dapat mengubah data.' : hasil.message, 'galat');
        tampilkanGalatField(hasil.errors);
    } catch (error) {
        if (error.message !== 'Sesi berakhir') {
            console.error('Gagal menyimpan:', error);
            tampilkanPesanForm('Tidak dapat menghubungi server.', 'galat');
        }
    } finally {
        tombolSimpan.disabled = false;
    }
});

async function hapusMahasiswa(mhs) {
    if (!confirm(`Hapus data ${mhs.nama} (${mhs.nim})?`)) return;
    try {
        const { ok, status, hasil } = await panggilApi(`/mahasiswa?id=${mhs.id}`, { method: 'DELETE', auth: true });
        if (!ok) {
            tampilkanStatus(status === 403 ? 'Akses ditolak: hanya admin yang dapat menghapus data.' : hasil.message, 'galat');
            return;
        }
        await muatDataMahasiswa();
    } catch (error) {
        if (error.message !== 'Sesi berakhir') {
            console.error('Gagal menghapus:', error);
            tampilkanStatus('Tidak dapat menghubungi server.', 'galat');
        }
    }
}

document.querySelector('#tombol-muat').addEventListener('click', muatDataMahasiswa);
document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
