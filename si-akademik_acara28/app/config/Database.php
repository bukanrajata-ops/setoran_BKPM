<?php
class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            // Acara 28: kredensial basis data pada hosting (AeonFree)
            $host = 'sql311.iceiy.com';
            $port = 3306;
            $db = 'icei_43384046_dbworkshop';
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, 'icei_43384046', 'Rajata78', $options);
            } catch (PDOException $e) {
                http_response_code(500);
                // 'detail' dihapus setelah koneksi berhasil; galat asli dicatat di log server
                error_log('Koneksi database gagal: ' . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Koneksi database gagal',
                ]);
                exit;
            }
        }
        return self::$connection;
    }
}