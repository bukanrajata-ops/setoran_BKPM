// Acara 27 - Langkah 1: form tambah data (Create) dengan FormData + header Authorization.
if (wajibAdmin()) {
    const form = document.querySelector('#form-mahasiswa');
    const inputFoto = document.querySelector('#foto');
    const pratinjau = document.querySelector('#pratinjau-foto');
    const tombolSimpan = document.querySelector('#tombol-simpan');

    inputFoto.addEventListener('change', () => {
        const file = inputFoto.files[0];
        const galat = Validasi.foto(file);
        form.querySelector('.galat-field[data-field="foto"]').textContent = galat;
        const tampil = file && !galat;
        pratinjau.hidden = !tampil;
        if (tampil) pratinjau.src = URL.createObjectURL(file); else pratinjau.removeAttribute('src');
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const file = inputFoto.files[0] || null;
        const data = {
            nim: form.elements.nim.value.trim(),
            nama: form.elements.nama.value.trim(),
            prodi: form.elements.prodi.value.trim(),
        };
        // Tugas mandiri 2: validasi di sisi View sebelum data dikirim ke Controller
        const galat = Validasi.mahasiswa(data, file);
        tampilkanGalatField(form, galat);
        if (Object.keys(galat).length > 0) {
            Notifikasi.tampil('Periksa kembali isian form.', 'galat');
            return;
        }

        tombolSimpan.disabled = true;
        try {
            const formData = new FormData();            // Content-Type tidak diatur manual (boundary dari browser)
            formData.append('nim', data.nim);
            formData.append('nama', data.nama);
            formData.append('prodi', data.prodi);
            if (file) formData.append('foto', file);    // foto opsional
            const { ok, status, hasil } = await panggilApi('/mahasiswa', { method: 'POST', auth: true, body: formData });
            if (ok) {
                Notifikasi.simpanUntukBerikut(`Data ${data.nama} berhasil ditambahkan.`);
                window.location.href = 'dashboard.html';
                return;
            }
            tampilkanGalatField(form, hasil.errors);
            Notifikasi.tampil(status === 403 ? 'Akses ditolak: hanya admin yang dapat menambah data.' : `Gagal menambah data: ${hasil.message}`, 'galat');
        } catch (error) {
            if (error.message !== 'Sesi berakhir') {
                console.error('Gagal menyimpan:', error);
                Notifikasi.tampil('Tidak dapat menghubungi server.', 'galat');
            }
        } finally {
            tombolSimpan.disabled = false;
        }
    });
}
