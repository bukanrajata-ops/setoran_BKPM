// Acara 27 - Langkah 2: form ubah data (Update). id dibaca dari query string: form_edit.html?id=3
if (wajibAdmin()) {
    const form = document.querySelector('#form-mahasiswa');
    const statusEl = document.querySelector('#status');
    const inputFoto = document.querySelector('#foto');
    const pratinjau = document.querySelector('#pratinjau-foto');
    const tombolSimpan = document.querySelector('#tombol-simpan');
    const id = new URLSearchParams(window.location.search).get('id');
    let fotoLama = null;

    function tampilkanPratinjau(src) {
        pratinjau.hidden = !src;
        if (src) pratinjau.src = src; else pratinjau.removeAttribute('src');
    }

    // Memuat data lama ke form: GET /mahasiswa?id=.. (MahasiswaController::index)
    async function muatDataUntukEdit() {
        if (!id || !/^[1-9][0-9]*$/.test(id)) {
            statusEl.textContent = 'Parameter id tidak valid.';
            statusEl.className = 'status galat';
            return;
        }
        try {
            const { ok, status, hasil } = await panggilApi(`/mahasiswa?id=${id}`);
            if (!ok) {
                statusEl.textContent = status === 404 ? 'Data tidak ditemukan.' : (hasil.message || 'Gagal memuat data.');
                statusEl.className = 'status galat';
                return;
            }
            const mhs = hasil.data;
            form.elements.nim.value = mhs.nim;
            form.elements.nama.value = mhs.nama;
            form.elements.prodi.value = mhs.prodi;
            fotoLama = mhs.foto || null;
            tampilkanPratinjau(urlFoto(fotoLama));
            statusEl.hidden = true;
            form.hidden = false;
            form.elements.nim.focus();
        } catch (error) {
            console.error('Gagal memuat data:', error);
            statusEl.textContent = 'Gagal memuat data. Pastikan server berjalan.';
            statusEl.className = 'status galat';
        }
    }

    inputFoto.addEventListener('change', () => {
        const file = inputFoto.files[0];
        const galat = Validasi.foto(file);
        form.querySelector('.galat-field[data-field="foto"]').textContent = galat;
        if (!file || galat) { tampilkanPratinjau(urlFoto(fotoLama)); return; }
        tampilkanPratinjau(URL.createObjectURL(file));
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const file = inputFoto.files[0] || null;
        const data = {
            nim: form.elements.nim.value.trim(),
            nama: form.elements.nama.value.trim(),
            prodi: form.elements.prodi.value.trim(),
        };
        const galat = Validasi.mahasiswa(data, file);      // Tugas mandiri 2
        tampilkanGalatField(form, galat);
        if (Object.keys(galat).length > 0) {
            Notifikasi.tampil('Periksa kembali isian form.', 'galat');
            return;
        }

        tombolSimpan.disabled = true;
        try {
            const formData = new FormData();
            formData.append('nim', data.nim);
            formData.append('nama', data.nama);
            formData.append('prodi', data.prodi);
            if (file) formData.append('foto', file);        // tanpa foto baru: foto lama dipertahankan server
            // Catatan: PHP tidak mengisi $_POST/$_FILES pada method PUT, sehingga FormData tidak dapat
            // dikirim dengan method: 'PUT'. Pembaruan dikirim POST dengan _method=PUT (diteruskan Router ke update()).
            const { ok, status, hasil } = await panggilApi(`/mahasiswa?id=${id}&_method=PUT`, { method: 'POST', auth: true, body: formData });
            if (ok) {
                Notifikasi.simpanUntukBerikut(`Data ${data.nama} berhasil diperbarui.`);
                window.location.href = 'dashboard.html';
                return;
            }
            tampilkanGalatField(form, hasil.errors);
            Notifikasi.tampil(status === 403 ? 'Akses ditolak: hanya admin yang dapat mengubah data.' : `Gagal memperbarui data: ${hasil.message}`, 'galat');
        } catch (error) {
            if (error.message !== 'Sesi berakhir') {
                console.error('Gagal menyimpan:', error);
                Notifikasi.tampil('Tidak dapat menghubungi server.', 'galat');
            }
        } finally {
            tombolSimpan.disabled = false;
        }
    });

    muatDataUntukEdit();
}
