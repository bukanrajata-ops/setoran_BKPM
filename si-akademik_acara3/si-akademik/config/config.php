<?php
/**
 * config/config.php
 * Konfigurasi dasar aplikasi.
 * Belum terhubung ke database (MySQL) karena pada Acara 3 kita baru
 * fokus pada struktur MVC dan integrasi View dengan Bootstrap.
 * Data mahasiswa & dosen masih disimpan langsung di dalam Model.
 */

// Base URL project (sesuaikan jika folder di htdocs berbeda nama)
define('BASE_URL', '/si-akademik/public/');

// Diperlukan agar data "Tambah Mahasiswa" tetap tersimpan selama sesi browser aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
