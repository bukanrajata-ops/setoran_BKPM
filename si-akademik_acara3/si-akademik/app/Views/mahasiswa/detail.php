<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Mahasiswa - SI Akademik</title>
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

    <div class="card shadow-sm" style="max-width: 500px;">
      <div class="card-header bg-info text-white">
        <h4 class="mb-0">Detail Mahasiswa</h4>
      </div>
      <div class="card-body">
        <?php if ($mahasiswa): ?>
          <table class="table table-borderless mb-3">
            <tr>
              <th style="width: 40%">NIM</th>
              <td><?= htmlspecialchars($mahasiswa['nim']) ?></td>
            </tr>
            <tr>
              <th>Nama</th>
              <td><?= htmlspecialchars($mahasiswa['nama']) ?></td>
            </tr>
            <tr>
              <th>Prodi</th>
              <td><?= htmlspecialchars($mahasiswa['prodi']) ?></td>
            </tr>
          </table>
        <?php else: ?>
          <p class="text-danger">Data mahasiswa dengan NIM tersebut tidak ditemukan.</p>
        <?php endif; ?>
        <a href="?url=Mahasiswa" class="btn btn-secondary w-100">&laquo; Kembali</a>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
