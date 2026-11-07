// Acara 25 - Langkah 3: halaman login
const form = document.querySelector('#form-login');
const pesanEl = document.querySelector('#pesan');
const tombol = document.querySelector('#tombol-login');

function tampilkanPesan(teks, jenis = '') {
    pesanEl.textContent = teks;
    pesanEl.className = 'pesan ' + jenis;
    pesanEl.hidden = teks === '';
}

// Sudah login dan token belum kedaluwarsa: langsung ke dashboard
if (Sesi.token() && !Sesi.kedaluwarsa()) {
    window.location.href = 'dashboard.html';
} else if (Sesi.token()) {
    Sesi.hapus();
}

if (new URLSearchParams(window.location.search).get('sesi') === 'habis') {
    tampilkanPesan('Sesi berakhir, silakan login kembali.', 'info');
}

document.querySelector('#tampil-password').addEventListener('change', (e) => {
    form.elements.password.type = e.target.checked ? 'text' : 'password';
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    tombol.disabled = true;
    tampilkanPesan('Memproses...', 'info');
    try {
        const { ok, hasil } = await panggilApi('/login', {
            method: 'POST',
            body: { username: form.elements.username.value.trim(), password: form.elements.password.value },
        });
        if (ok) {
            Sesi.simpan(hasil.data.token, hasil.data.role);
            window.location.href = 'dashboard.html';
            return;
        }
        tampilkanPesan(hasil.message || 'Login gagal', 'galat');
    } catch (error) {
        console.error('Gagal login:', error);
        tampilkanPesan('Tidak dapat menghubungi server. Pastikan server berjalan.', 'galat');
    } finally {
        tombol.disabled = false;
    }
});
