<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Mahasiswa - SI Akademik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4 mb-5">

    <h3 class="fw-bold mb-3">Politeknik Negeri Jember</h3>

    <ul class="nav nav-pills mb-4">
      <li class="nav-item">
        <a class="nav-link active" href="?url=Mahasiswa">Data Mahasiswa</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="?url=Dosen">Data Dosen</a>
      </li>
    </ul>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">DATA MAHASISWA</h4>

      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark">
              <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Prodi</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($mahasiswa)): ?>
                <tr>
                  <td colspan="4" class="text-center py-3">Belum ada data mahasiswa.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($mahasiswa as $mhs): ?>
                  <tr>
                    <td><?= htmlspecialchars($mhs['nim']) ?></td>
                    <td><?= htmlspecialchars($mhs['nama']) ?></td>
                    <td><?= htmlspecialchars($mhs['prodi']) ?></td>
                    <td>
                      <a href="?url=mahasiswa/detail&nim=<?= urlencode($mhs['nim']) ?>" class="btn btn-info btn-sm text-white">Detail</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
