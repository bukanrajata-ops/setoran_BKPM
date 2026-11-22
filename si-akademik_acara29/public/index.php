<?php
// CORS: diizinkan untuk seluruh resource yang melewati Router (Acara 24)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Preflight: browser mengirim OPTIONS sebelum POST/PUT/DELETE berheader Authorization.
// Dijawab 204 tanpa body dan tidak diteruskan ke Router.
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../app/core/autoload.php';
(new Router())->dispatch();
