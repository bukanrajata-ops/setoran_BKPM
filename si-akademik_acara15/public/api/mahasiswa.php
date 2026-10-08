<?php
// public/api/mahasiswa.php
// Acara 15 - API sederhana + response JSON untuk data mahasiswa.
//
//   GET  /api/mahasiswa           -> daftar seluruh mahasiswa
//   GET  /api/mahasiswa?id=1      -> satu mahasiswa berdasarkan id
//   POST /api/mahasiswa           -> tambah mahasiswa (body JSON)
//
// Endpoint ini memakai lapisan yang sudah ada: Database (PDO), Repository,
// dan MahasiswaService (validasi pada POST sama dengan form web).
//
// Bentuk response selalu sama:
//   { "success": true|false, "message": "...", "data": ... }
//   (pada kegagalan validasi ada "errors": { "field": "pesan" })

require_once __DIR__ . '/../../config/config.php';

use App\Core\Database;
use App\Core\Logger;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use App\Services\MahasiswaService;

header('Content-Type: application/json; charset=utf-8');

/**
 * Mengirim response JSON lalu menghentikan script.
 */
function respond(int $status, string $message, $data = null, array $errors = []): void
{
    http_response_code($status);

    $body = [
        'success' => $status >= 200 && $status < 300,
        'message' => $message,
    ];

    if ($body['success']) {
        $body['data'] = $data;
    } elseif (!empty($errors)) {
        $body['errors'] = $errors;
    }

    echo json_encode(
        $body,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
    );
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if (!in_array($method, ['GET', 'POST'], true)) {
    header('Allow: GET, POST');
    respond(405, 'Method ' . $method . ' tidak didukung. Gunakan GET atau POST.');
}

try {
    $database = new Database(); // koneksi PDO (Acara 9)
    $service = new MahasiswaService(
        new MahasiswaRepository($database),
        new ProdiRepository($database)
    );

    if ($method === 'GET') {
        handleGet($service);
    } else {
        handlePost($service);
    }
} catch (Throwable $e) {
    // Detail teknis hanya masuk log (Acara 14), pengguna API hanya melihat
    // pesan umum.
    Logger::error('API mahasiswa', $e);
    respond(500, 'Terjadi kesalahan pada server.');
}

// ---------------------------------------------------------------------
// GET: seluruh data atau satu data (?id=)
// ---------------------------------------------------------------------
function handleGet(MahasiswaService $service): void
{
    if (!isset($_GET['id'])) {
        respond(200, 'Data berhasil diambil', $service->listForApi());
    }

    $id = (string) $_GET['id'];

    if (!ctype_digit($id) || (int) $id < 1) {
        respond(400, 'Parameter id harus berupa angka positif.');
    }

    $mahasiswa = $service->findForApi((int) $id);

    if ($mahasiswa === null) {
        respond(404, 'Data mahasiswa tidak ditemukan.');
    }

    respond(200, 'Data berhasil diambil', $mahasiswa);
}

// ---------------------------------------------------------------------
// POST: tambah data. Body harus JSON, contoh:
//   { "nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com",
//     "prodi_id": 1 }
// angkatan (default dari 2 digit awal NIM) dan status (default "aktif")
// bersifat opsional.
// ---------------------------------------------------------------------
function handlePost(MahasiswaService $service): void
{
    $raw = trim((string) file_get_contents('php://input'));

    if ($raw === '') {
        respond(400, 'Body request kosong. Kirim data dalam format JSON.');
    }

    $json = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
        respond(400, 'Format JSON tidak valid.');
    }

    // Hanya field yang dikenal yang diteruskan ke Service, dan nilainya
    // harus berupa teks/angka (bukan array/objek bersarang).
    $input = ['status' => 'aktif'];
    $errors = [];

    foreach (['nim', 'nama', 'email', 'prodi_id', 'angkatan', 'status'] as $field) {
        if (!array_key_exists($field, $json)) {
            continue;
        }

        if (!is_scalar($json[$field]) && $json[$field] !== null) {
            $errors[$field] = 'Nilai ' . $field . ' harus berupa teks atau angka';
            continue;
        }

        $input[$field] = $json[$field];
    }

    if (!empty($errors)) {
        respond(422, 'Data tidak valid.', null, $errors);
    }

    // Angkatan bila tidak dikirim: diturunkan dari 2 digit awal NIM
    // (23004 -> 2023), sama seperti aturan pada model Mahasiswa.
    if (!array_key_exists('angkatan', $input)) {
        $nim = (string) ($input['nim'] ?? '');
        $input['angkatan'] = (ctype_digit($nim) && strlen($nim) >= 2)
            ? '20' . substr($nim, 0, 2)
            : date('Y');
    }

    $result = $service->create($input);

    if ($result['success']) {
        header('Location: ' . BASE_URL . '/api/mahasiswa?id=' . $result['id']);
        respond(
            201,
            'Data mahasiswa berhasil ditambahkan',
            $service->findForApi($result['id'])
        );
    }

    if (empty($result['errors'])) {
        // Kegagalan database (sudah dicatat ke log oleh Service).
        respond(500, 'Terjadi kesalahan pada server.');
    }

    // 409 Conflict untuk NIM duplikat, 422 untuk kesalahan validasi lain.
    $status = (($result['errors']['nim'] ?? '') === 'NIM sudah terdaftar') ? 409 : 422;

    respond($status, $result['message'], null, $result['errors']);
}
