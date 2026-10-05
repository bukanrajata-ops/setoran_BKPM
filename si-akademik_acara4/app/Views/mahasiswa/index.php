<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">DATA MAHASISWA</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Prodi</th>
                    <th>Angkatan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarMahasiswa as $mhs): ?>
                <tr>
                    <td><?= htmlspecialchars($mhs->getNim()) ?></td>
                    <td><?= htmlspecialchars($mhs->getNama()) ?></td>
                    <td><?= htmlspecialchars($mhs->getProdi()) ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($mhs->getAngkatan()) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
