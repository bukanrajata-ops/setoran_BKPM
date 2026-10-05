<?php
require __DIR__ . '/../config/config.php';

use App\Models\Mahasiswa;

$routes = require __DIR__ . '/../routes/web.php';
$url    = $_GET['url'] ?? 'Mahasiswa';
$active = strtolower($url);

$daftarMahasiswa = [
    new Mahasiswa('23001', 'Andi', 'Teknik Informatika'),
    new Mahasiswa('23002', 'Budi', 'Teknik Informatika'),
    new Mahasiswa('23003', 'Citra', 'Sistem Informasi'),
    new Mahasiswa('23004', 'Dinda', 'Teknik Informatika'),
    new Mahasiswa('23005', 'Waluyo', 'Teknik Informatika'),
    new Mahasiswa('23010', 'Asep', 'Sistem Informasi'),
];

$daftarDosen = [
    ['nidn' => '0028069702', 'nama' => 'Ulfa Emi Rahmawati, S.Kom., M.Kom.', 'matkul' => 'Literasi Digital'],
    ['nidn' => '0009059403', 'nama' => 'Qonitatul Hasanah, S.ST., M.Tr.T', 'matkul' => 'Workshop Sistem Informasi Web Server'],
    ['nidn' => '0009109304', 'nama' => 'Raditya Arief Pratama, S.Kom., M.Eng', 'matkul' => 'Workshop Mobile Applications Advance'],
];

if (!array_key_exists($url, $routes)) {
    http_response_code(404);
    echo "Halaman tidak ditemukan";
    exit;
}

$content = $routes[$url];
require __DIR__ . '/../app/Views/layouts/main.php';
