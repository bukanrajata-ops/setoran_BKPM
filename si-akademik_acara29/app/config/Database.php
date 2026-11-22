<?php
class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $host = '127.0.0.1';
            $port = 3307;
            $db = 'db_workshop';
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, 'root', '', $options);
            } catch (PDOException $e) {
                http_response_code(500);
                // 'detail' hanya untuk diagnosis sementara, hapus setelah koneksi berhasil
                echo json_encode([
                    'success' => false,
                    'message' => 'Koneksi database gagal',
                    'detail'  => $e->getMessage(),
                ]);
                exit;
            }
        }
        return self::$connection;
    }
}