<?php
// app/Models/Mahasiswa.php
namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;

    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->nim   = $nim;
        $this->nama  = $nama;
        $this->prodi = $prodi;
    }

    // Getter (encapsulation: property private, hanya bisa diakses lewat method)
    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    // Tugas Mandiri Acara 4:
    // Mengembalikan angkatan berdasarkan 2 digit awal NIM
    // Asumsi format NIM: 2 digit awal = tahun masuk (contoh: 23001 -> angkatan 2023)
    public function getAngkatan(): string
    {
        $duaDigitAwal = substr($this->nim, 0, 2);
        return '20' . $duaDigitAwal;
    }

    public function getLabel(): string
    {
        return "{$this->nim} - {$this->nama} ({$this->prodi})";
    }
}
