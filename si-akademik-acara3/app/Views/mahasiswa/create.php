<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tambah Mahasiswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4 mb-5">
    <h1 class="mb-4">Tambah Mahasiswa</h1>

    <div class="card shadow-sm" style="max-width: 500px;">
      <div class="card-body">

        <!--
          action masih "#" karena Controller belum dibuat.
          Nanti mulai Acara 4, action akan diarahkan ke Controller,
          misalnya: action="index.php?url=mahasiswa/store" method="POST"
        -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="text" class="form-control" id="nim" name="nim" required>
          </div>
          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>
          <div class="mb-3">
            <label for="prodi" class="form-label">Program Studi</label>
            <select class="form-control" id="prodi" name="prodi" required>
              <option value="" disabled selected>-- Pilih Prodi --</option>
              <option value="Teknik Informatika">Teknik Informatika</option>
              <option value="Sistem Informasi">Sistem Informasi</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
          <a href="index.php" class="btn btn-secondary w-100 mt-2">&laquo; Kembali</a>
        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
