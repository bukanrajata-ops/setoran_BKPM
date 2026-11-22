<?php
class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            // Acara 30: kredensial dibaca dari berkas terpisah (credentials.php), bukan ditulis di sini.
            $berkas = __DIR__ . '/credentials.php';
            $cred = is_file($berkas) ? require $berkas : null;
            if (!is_array($cred)) {
                error_log('Berkas credentials.php tidak ditemukan atau tidak valid');
                http_response_code(500);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Koneksi database gagal']);
                exit;
            }
            $charset = 'utf8mb4';
            $dsn = "mysql:host={$cred['host']};port={$cred['port']};dbname={$cred['database']};charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, $cred['icei_43384046'], $cred['Rajata78'], $options);
            } catch (PDOException $e) {
                http_response_code(500);
                header('Content-Type: application/json');
                // galat asli dicatat di log server, tidak dikirim ke pengguna
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
