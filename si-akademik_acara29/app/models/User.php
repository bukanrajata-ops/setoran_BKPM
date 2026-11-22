<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Tugas Praktik Mandiri 1: validasi format email pada lapisan Model.
    // Dipanggil sebelum data disimpan; melempar InvalidArgumentException jika tidak valid.
    private function validateEmail(string $email): void {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Format email tidak valid');
        }
    }

    public function existsByUsername(string $username): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function existsByEmail(string $email): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :e');
        $stmt->execute([':e' => $email]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(array $data): string {
        $this->validateEmail($data['email']);

        // Username/email UNIQUE: cek dulu agar menghasilkan 409, bukan error 500 dari database
        if ($this->existsByUsername($data['username'])) {
            throw new DomainException('Username sudah terdaftar');
        }
        if ($this->existsByEmail($data['email'])) {
            throw new DomainException('Email sudah terdaftar');
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (username, email, password) VALUES (:u, :e, :p)'
        );
        $stmt->execute([':u' => $data['username'], ':e' => $data['email'], ':p' => $data['password']]);
        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username) {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :u');
        $stmt->execute([':u' => $username]);
        return $stmt->fetch();
    }
}
