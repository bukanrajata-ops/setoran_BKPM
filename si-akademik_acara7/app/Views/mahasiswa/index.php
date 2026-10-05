<div class="card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">DATA MAHASISWA</h4>
        <a href="<?= BASE_URL ?>/mahasiswa/create" class="btn btn-sm btn-light">+ Tambah Mahasiswa</a>
    </div>
    <div class="card-body">
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
                <?php foreach ($daftarMahasiswa as $mhs): ?>
                <?php
                    $statusColor = match ($mhs->getStatus()) {
                        'aktif' => 'success',
                        'cuti'  => 'warning',
                        'lulus' => 'secondary',
                        default => 'light',
                    };
                ?>
                <tr>
                    <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                    <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                    <td><?= htmlspecialchars($mhs->getProdi()) ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($mhs->getAngkatan()) ?></span></td>
                    <td><span class="badge bg-<?= $statusColor ?>"><?= htmlspecialchars(ucfirst($mhs->getStatus())) ?></span></td>
                    <td>
                        
                        <a href="<?= BASE_URL ?>/mahasiswa/<?= urlencode($mhs->getNim()) ?>" class="btn btn-sm btn-info text-white">Detail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>