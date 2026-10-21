<?php
class Mahasiswa {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM mahasiswa ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id) {
        $stmt = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Tugas Praktik Mandiri 2: validasi NIM duplikat pada lapisan Model.
    // $exceptId dipakai saat update agar NIM milik data itu sendiri tidak dihitung duplikat.
    public function existsByNim(string $nim, ?int $exceptId = null): bool {
        $sql = 'SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim';
        $params = [':nim' => $nim];
        if ($exceptId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string {
        if ($this->existsByNim($data['nim'])) {
            throw new DomainException('NIM sudah terdaftar');
        }
        $stmt = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi) VALUES (:nim, :nama, :prodi)'
        );
        $stmt->execute([':nim' => $data['nim'], ':nama' => $data['nama'], ':prodi' =>
            $data['prodi']]);
        return $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void {
        if ($this->existsByNim($data['nim'], $id)) {
            throw new DomainException('NIM sudah terdaftar');
        }
        $stmt = $this->db->prepare(
            'UPDATE mahasiswa SET nim = :nim, nama = :nama, prodi = :prodi WHERE id = :id'
        );
        $stmt->execute([':nim' => $data['nim'], ':nama' => $data['nama'], ':prodi' =>
            $data['prodi'], ':id' => $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
