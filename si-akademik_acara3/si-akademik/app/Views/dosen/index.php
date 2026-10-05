<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Dosen - SI Akademik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4 mb-5">

    <h3 class="fw-bold mb-3">Politeknik Negeri Jember</h3>

    <ul class="nav nav-pills mb-4">
      <li class="nav-item">
        <a class="nav-link" href="?url=Mahasiswa">Data Mahasiswa</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="?url=Dosen">Data Dosen</a>
      </li>
    </ul>

    <div class="card shadow-sm">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0">Daftar Dosen</h4>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-striped mb-0">
            <thead class="table-dark">
              <tr>
                <th>NIDN</th>
                <th>Nama</th>
                <th>Mata Kuliah</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dosen as $d): ?>
                <tr>
                  <td><?= htmlspecialchars($d['nidn']) ?></td>
                  <td><?= htmlspecialchars($d['nama']) ?></td>
                  <td><?= htmlspecialchars($d['matkul']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
