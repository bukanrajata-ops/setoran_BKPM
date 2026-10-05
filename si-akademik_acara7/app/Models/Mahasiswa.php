<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Mahasiswa extends Model
{
    private string $nim;
    private string $nama;
    private string $prodi;
    private string $angkatan;
    private string $status;

    public function __construct(string $nim, string $nama, string $prodi, string $angkatan = '', string $status = 'aktif')
    {
        $this->nim   = $nim;
        $this->nama  = $nama;
        $this->prodi = $prodi;
        $this->status = $status;

        $this->angkatan = $angkatan !== '' ? $angkatan : ('20' . substr($nim, 0, 2));
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

    public function getAngkatan(): string
    {
        return $this->angkatan;
    }

    // Tugas Mandiri Acara 7: kolom status ('aktif','cuti','lulus')
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getLabel(): string
    {
        return "{$this->nim} - {$this->nama} ({$this->prodi})";
    }


    // ACARA 7 - Model terhubung ke database (menggantikan
  
    public static function all(): array
    {
        $stmt = self::db()->query("
            SELECT m.nim, m.nama, m.angkatan, m.status, p.nama AS prodi_nama
            FROM mahasiswa m
            JOIN prodi p ON m.prodi_id = p.id
            ORDER BY m.nim ASC
        ");

        $daftar = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $daftar[] = new self(
                $row['nim'],
                $row['nama'],
                $row['prodi_nama'],
                (string) $row['angkatan'],
                $row['status']
            );
        }

        return $daftar;
    }

    /**
     * Cari satu mahasiswa berdasarkan NIM. Dipakai oleh
     * MahasiswaController::show() untuk halaman detail.
     */
    public static function findByNim(string $nim): ?self
    {
        $stmt = self::db()->prepare("
            SELECT m.nim, m.nama, m.angkatan, m.status, p.nama AS prodi_nama
            FROM mahasiswa m
            JOIN prodi p ON m.prodi_id = p.id
            WHERE m.nim = :nim
        ");
        $stmt->execute(['nim' => $nim]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new self(
            $row['nim'],
            $row['nama'],
            $row['prodi_nama'],
            (string) $row['angkatan'],
            $row['status']
        );
    }
}
