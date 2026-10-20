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

    public function existsByNim(string $nim, ?string $exceptNim = null): bool
    {
        $sql = "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim";
        $params = ['nim' => $nim];

        // Saat update, NIM milik data yang sedang diubah tidak dihitung.
        if ($exceptNim !== null) {
            $sql .= " AND nim <> :except_nim";
            $params['except_nim'] = $exceptNim;
        }

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * @param array $data nim, nama, email, prodi_id, angkatan, status
     *                    (sudah divalidasi oleh MahasiswaService)
     * @return int id baris yang baru dibuat
     */
    public function create(array $data): int
    {
        $stmt = $this->db()->prepare(
            "INSERT INTO mahasiswa
            (nim, nama, email, prodi_id, angkatan, status)
            VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );

        $stmt->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => (int) $data['prodi_id'],
            'angkatan' => (int) $data['angkatan'],
            'status' => $data['status'],
        ]);

        return (int) $this->db()->lastInsertId();
    }

    public function updateByNim(string $oldNim, array $data): bool
    {
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
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'prodi_id' => (int) $data['prodi_id'],
            'angkatan' => (int) $data['angkatan'],
            'status' => $data['status'],
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

    // ---------------------------------------------------------------
    // Acara 15 - API: data dikembalikan sebagai array asosiatif (siap
    // di-json_encode), termasuk id dan email yang tidak ada di objek
    // Mahasiswa. LEFT JOIN agar data tetap muncul walau prodinya hilang.
    // ---------------------------------------------------------------

    private const API_SELECT =
        "SELECT m.id, m.nim, m.nama, m.email, m.prodi_id,
                p.nama AS prodi, m.angkatan, m.status
         FROM mahasiswa m
         LEFT JOIN prodi p ON m.prodi_id = p.id";

    public function allForApi(): array
    {
        return $this->db()
            ->query(self::API_SELECT . " ORDER BY m.id ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByIdForApi(int $id): ?array
    {
        $stmt = $this->db()->prepare(self::API_SELECT . " WHERE m.id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
