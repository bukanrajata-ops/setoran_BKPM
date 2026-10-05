<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4 mb-5">
    <h1 class="mb-4">Daftar Mahasiswa</h1>

    <a href="create.php" class="btn btn-primary mb-3">Tambah Mahasiswa</a>

    <div class="card shadow-sm">
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
              <!-- Data akan diisi dari Controller mulai Acara 4 -->
              <!-- Baris contoh di bawah ini hanya untuk memastikan tampilan tabel sudah rapi -->
              <tr>
                <td>23001</td>
                <td>Andi</td>
                <td>Teknik Informatika</td>
                <td><a href="#" class="btn btn-info btn-sm text-white">Detail</a></td>
              </tr>
              <tr>
                <td>23002</td>
                <td>Budi</td>
                <td>Sistem Informasi</td>
                <td><a href="#" class="btn btn-info btn-sm text-white">Detail</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
