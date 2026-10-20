<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

// Acara 13: Repository untuk tabel prodi. Dipakai MahasiswaService untuk
// mengambil daftar prodi dan memastikan prodi yang dipilih memang tersedia.
class ProdiRepository
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

    public function all(): array
    {
        return $this->db()
            ->query("SELECT * FROM prodi ORDER BY kode ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existsById(int $id): bool
    {
        $stmt = $this->db()->prepare(
            "SELECT COUNT(*) FROM prodi WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        return (int) $stmt->fetchColumn() > 0;
    }
}
