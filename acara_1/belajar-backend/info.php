<?php
// File: info.php
// Tugas Mandiri Acara 1
$nama  = "Rajata Pringgit Bintang Bihar";
$nim   = "E41250264";
$waktu = date("Y-m-d H:i:s");
$versiPhp = phpversion();
$os    = PHP_OS;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Info Mahasiswa & Server</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 40px; }
    table { border-collapse: collapse; width: 100%; max-width: 500px; background: #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ccc; padding: 10px 15px; text-align: left; }
    th { background: #2c3e50; color: #fff; width: 40%; }
  </style>
</head>
<body>
  <h1>Informasi Mahasiswa dan Server</h1>
  <table>
    <tr><th>Nama</th><td><?= htmlspecialchars($nama) ?></td></tr>
    <tr><th>NIM</th><td><?= htmlspecialchars($nim) ?></td></tr>
    <tr><th>Waktu Server</th><td><?= htmlspecialchars($waktu) ?></td></tr>
    <tr><th>Versi PHP</th><td><?= htmlspecialchars($versiPhp) ?></td></tr>
    <tr><th>Sistem Operasi Server</th><td><?= htmlspecialchars($os) ?></td></tr>
  </table>
</body>
</html>