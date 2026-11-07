<?php
class Response {
    public static function json(int $httpCode, bool $success, string $message, $data = null, array $errors = []): void {
        http_response_code($httpCode);
        header('Content-Type: application/json');

        $body = [
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
            'timestamp' => date('c'),
        ];
        // Tugas Praktik Mandiri 1: daftar kesalahan validasi per field
        if (!empty($errors)) {
            $body['errors'] = $errors;
        }

        // Tugas Praktik Mandiri 2: pretty print hanya saat mode pengembangan
        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;
        if (defined('APP_ENV') && APP_ENV === 'development') {
            $flags |= JSON_PRETTY_PRINT;
        }

        echo json_encode($body, $flags);
        exit;
    }
}
