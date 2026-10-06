<!-- app/Views/dosen/index.php -->
<div class="card shadow-sm">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">Daftar Dosen</h4>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>NIDN</th>
                    <th>Nama</th>
                    <th>Mata Kuliah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarDosen as $dsn): ?>
                <tr>
                    <td><?= htmlspecialchars($dsn['nidn']) ?></td>
                    <td><?= htmlspecialchars($dsn['nama']) ?></td>
                    <td><?= htmlspecialchars($dsn['matkul']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
