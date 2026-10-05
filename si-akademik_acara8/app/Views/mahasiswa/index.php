<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0">DATA MAHASISWA</h4>
        <a href="<?= BASE_URL ?>/mahasiswa/create" class="btn btn-sm btn-light">+ Tambah Mahasiswa</a>
    </div>

    <div class="card-body">
        <form method="get" action="<?= BASE_URL ?>/mahasiswa" class="row g-2 mb-3">
            <div class="col-md-10">
                <input
                    class="form-control"
                    name="q"
                    value="<?= htmlspecialchars($keyword ?? '') ?>"
                    placeholder="Cari berdasarkan NIM atau nama..."
                >
            </div>

            <div class="col-md-2 d-grid">
                <button class="btn btn-primary">Cari</button>
            </div>
        </form>

        <?php if (($keyword ?? '') !== ''): ?>
            <div class="alert alert-info py-2">
                Hasil pencarian untuk:
                <strong><?= htmlspecialchars($keyword) ?></strong>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($daftarMahasiswa)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Data tidak ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($daftarMahasiswa as $mhs): ?>
                        <?php
                        $color = match ($mhs->getStatus()) {
                            'aktif' => 'success',
                            'cuti' => 'warning',
                            'lulus' => 'secondary',
                            default => 'light'
                        };
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                            <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                            <td><?= htmlspecialchars($mhs->getProdi()) ?></td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    <?= htmlspecialchars($mhs->getAngkatan()) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $color ?>">
                                    <?= htmlspecialchars(ucfirst($mhs->getStatus())) ?>
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <a
                                    href="<?= BASE_URL ?>/mahasiswa/<?= urlencode($mhs->getNim()) ?>"
                                    class="btn btn-sm btn-info text-white"
                                >
                                    Detail
                                </a>

                                <a
                                    href="<?= BASE_URL ?>/mahasiswa/<?= urlencode($mhs->getNim()) ?>/edit"
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <form
                                    class="d-inline"
                                    method="post"
                                    action="<?= BASE_URL ?>/mahasiswa/<?= urlencode($mhs->getNim()) ?>/delete"
                                    data-confirm="Hapus data mahasiswa ini?"
                                >
                                    <button class="btn btn-sm btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>