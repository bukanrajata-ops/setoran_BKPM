<?php
// app/Core/Model.php
// Base Model: tempat menaruh hal-hal umum yang nantinya dipakai semua
// Model, misalnya koneksi ke database saat data mahasiswa/dosen sudah
// tidak lagi hardcode di dalam kode.
namespace App\Core;

abstract class Model
{
    // Sengaja masih kosong pada Acara 6 ini karena Model (Mahasiswa,
    // Dosen) belum terhubung ke database. Nanti method seperti
    // getConnection() bisa ditambahkan di sini.
}
