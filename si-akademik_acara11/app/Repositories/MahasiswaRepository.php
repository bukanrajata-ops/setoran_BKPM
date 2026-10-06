<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Mahasiswa;
use PDO;

class MahasiswaRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    private function db(): PDO
    {
        return $this->database->getConnection();
    }

    public function all(string $keyword = ''): array
    {
        if ($keyword !== '') {
            $stmt = $this->db()->prepare(
                "SELECT m.nim, m.nama, m.angkatan, m.status,
                        p.nama AS prodi_nama
                 FROM mahasiswa m
                 JOIN prodi p ON m.prodi_id = p.id
                 WHERE m.nim LIKE :keyword
                    OR m.nama LIKE :keyword
                 ORDER BY m.nim ASC"
            );

            $stmt->execute([
                'keyword' => '%' . $keyword . '%'
            ]);
        } else {
            $stmt = $this->db()->query(
                "SELECT m.nim, m.nama, m.angkatan, m.status,
                        p.nama AS prodi_nama
                 FROM mahasiswa m
                 JOIN prodi p ON m.prodi_id = p.id
                 ORDER BY m.nim ASC"
            );
        }

        $daftar = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $daftar[] = new Mahasiswa(
                $row['nim'],
                $row['nama'],
                $row['prodi_nama'],
                (string) $row['angkatan'],
                $row['status']
            );
        }

        return $daftar;
    }

    public function findByNimData(string $nim): ?array
    {
        $stmt = $this->db()->prepare(
            "SELECT * FROM mahasiswa WHERE nim = :nim"
        );

        $stmt->execute([
            'nim' => $nim
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByNim(string $nim): ?Mahasiswa
    {
        $stmt = $this->db()->prepare(
            "SELECT m.nim, m.nama, m.angkatan, m.status,
                    p.nama AS prodi_nama
             FROM mahasiswa m
             JOIN prodi p ON m.prodi_id = p.id
             WHERE m.nim = :nim"
        );

        $stmt->execute([
            'nim' => $nim
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Mahasiswa(
            $row['nim'],
            $row['nama'],
            $row['prodi_nama'],
            (string) $row['angkatan'],
            $row['status']
        );
    }

    public function create(
        string $nim,
        string $nama,
        string $email,
        int $prodiId,
        int $angkatan,
        string $status = 'aktif'
    ): bool {
        $stmt = $this->db()->prepare(
            "INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan, status)
            VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );

        return $stmt->execute([
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
            'status' => $status
        ]);
    }

    public function updateByNim(
        string $oldNim,
        string $nim,
        string $nama,
        string $email,
        int $prodiId,
        int $angkatan,
        string $status
    ): bool {
        $stmt = $this->db()->prepare(
            "UPDATE mahasiswa
             SET nim = :nim,
                 nama = :nama,
                 email = :email,
                 prodi_id = :prodi_id,
                 angkatan = :angkatan,
                 status = :status
             WHERE nim = :old_nim"
        );

        return $stmt->execute([
            'old_nim' => $oldNim,
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'prodi_id' => $prodiId,
            'angkatan' => $angkatan,
            'status' => $status
        ]);
    }

    public function deleteByNim(string $nim): bool
    {
        $stmt = $this->db()->prepare(
            "DELETE FROM mahasiswa WHERE nim = :nim"
        );

        return $stmt->execute([
            'nim' => $nim
        ]);
    }
}
