<?php

namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;
    private string $angkatan;
    private string $status;

    public function __construct(
        string $nim,
        string $nama,
        string $prodi,
        string $angkatan = '',
        string $status = 'aktif'
    ) {
        // Data dari database tetap dapat ditampilkan.
        // Validasi diterapkan melalui setter saat data diubah dari input.
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
        $this->angkatan = $angkatan !== ''
            ? $angkatan
            : ('20' . substr($nim, 0, 2));
        $this->status = $status;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function setNim(string $nim): void
    {
        if (!preg_match('/^\d+$/', $nim)) {
            throw new \InvalidArgumentException('NIM harus berupa angka.');
        }

        $this->nim = $nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);

        if ($nama === '') {
            throw new \InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }

        $this->nama = $nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function setProdi(string $prodi): void
    {
        $prodi = trim($prodi);

        if ($prodi === '') {
            throw new \InvalidArgumentException('Prodi tidak boleh kosong.');
        }

        $this->prodi = $prodi;
    }

    public function getAngkatan(): string
    {
        return $this->angkatan;
    }

    public function setAngkatan(string $angkatan): void
    {
        $angkatan = trim($angkatan);

        if (!preg_match('/^\d{4}$/', $angkatan)) {
            throw new \InvalidArgumentException('Angkatan harus terdiri dari 4 angka.');
        }

        $this->angkatan = $angkatan;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $status = strtolower(trim($status));
        $allowed = ['aktif', 'cuti', 'lulus'];

        if (!in_array($status, $allowed, true)) {
            throw new \InvalidArgumentException('Status mahasiswa tidak valid.');
        }

        $this->status = $status;
    }

    public function getLabel(): string
    {
        return "{$this->nim} - {$this->nama} ({$this->prodi})";
    }
}
