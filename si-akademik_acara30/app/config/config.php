<?php
// Mode aplikasi. 'development' => JSON rapi + galat tampil. 'production' => JSON ringkas + galat disembunyikan.
// Acara 30: diubah ke 'production' untuk lingkungan hosting.
define('APP_ENV', 'production');

if (APP_ENV === 'production') {
    // Acara 30, Langkah 3: galat PHP tidak boleh tampil ke pengguna (dapat membocorkan path server dan query),
    // tetapi tetap dicatat ke berkas log agar dapat ditelusuri pengembang.
    // Bila panel hosting menyediakan MultiPHP INI Editor, pengaturan yang sama dapat diisi di sana;
    // pengaturan di bawah ini berlaku di hosting yang tidak menyediakannya.
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    $logDir = __DIR__ . '/../logs';
    if (is_dir($logDir) && is_writable($logDir)) {
        ini_set('error_log', $logDir . '/php_errors.log');
    }
    error_reporting(E_ALL);

    // Graceful failure: galat tak tertangani dijawab JSON generik (tanpa detail), detail masuk log.
    set_exception_handler(function (Throwable $e): void {
        error_log('Exception tak tertangani: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: application/json');
        }
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada server'], JSON_UNESCAPED_UNICODE);
    });
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}
