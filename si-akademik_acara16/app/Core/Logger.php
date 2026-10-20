<?php
// app/Core/Logger.php
// Acara 14 - Logging.
// Mencatat kejadian penting/error ke storage/logs/app.log agar programmer
// bisa tahu kapan, apa, dan di bagian mana aplikasi bermasalah, tanpa harus
// menampilkan detail teknis tersebut kepada pengguna.
//
// PENTING: jangan pernah menulis informasi sensitif (password, dsb) ke log.
namespace App\Core;

use Throwable;

class Logger
{
    private const LOG_FILE = __DIR__ . '/../../storage/logs/app.log';

    public static function error(string $context, Throwable $e): void
    {
        self::write('ERROR', $context . ' - ' . $e->getMessage());
    }

    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }

    private static function write(string $level, string $message): void
    {
        $line = sprintf(
            '[%s] %s: %s%s',
            date('Y-m-d H:i:s'),
            $level,
            $message,
            PHP_EOL
        );

        // Parameter ke-3 (3) = append ke file, membuat file bila belum ada.
        error_log($line, 3, self::LOG_FILE);
    }
}
