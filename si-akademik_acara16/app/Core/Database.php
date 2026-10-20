<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
                $config['username'],
                $config['password']
            );

            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            // Acara 14 - Security & Logging: detail teknis (host, kredensial,
            // pesan asli PDO) dicatat ke log, TIDAK ditampilkan ke pengguna.
            Logger::error('Koneksi database gagal', $e);
            // Acara 15: dilempar sebagai exception (bukan die) supaya endpoint
            // API dapat membalas JSON; halaman web ditangani public/index.php.
            throw new \RuntimeException('Koneksi database gagal', 0, $e);
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
