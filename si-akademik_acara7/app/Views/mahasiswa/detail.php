<!-- app/Views/mahasiswa/detail.php -->
<div class="card shadow-sm" style="max-width: 500px;">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0">Detail Mahasiswa</h4>
    </div>
    <div class="card-body">
        <?php if ($mahasiswaDitemukan): ?>
            <table class="table table-borderless mb-3">
                <tr>
                    <th style="width: 40%">NIM</th>
                    <td><?= htmlspecialchars($mahasiswaDitemukan->getNim()) ?></td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td><?= htmlspecialchars($mahasiswaDitemukan->getNama()) ?></td>
                </tr>
                <tr>
                    <th>Prodi</th>
                    <td><?= htmlspecialchars($mahasiswaDitemukan->getProdi()) ?></td>
                </tr>
                <tr>
                    <th>Angkatan</th>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($mahasiswaDitemukan->getAngkatan()) ?></span></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= htmlspecialchars(ucfirst($mahasiswaDitemukan->getStatus())) ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p class="text-danger">Mahasiswa tidak ditemukan.</p>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/mahasiswa" class="btn btn-secondary w-100">&laquo; Kembali</a>
    </div>
</div>
