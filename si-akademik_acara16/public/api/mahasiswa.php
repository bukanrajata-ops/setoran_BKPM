<?php
// public/api/mahasiswa.php
// Acara 16 - RESTful API data mahasiswa (resource + HTTP method).
//
//   GET    /api/mahasiswa        -> daftar seluruh mahasiswa
//   GET    /api/mahasiswa/{id}   -> satu mahasiswa
//   POST   /api/mahasiswa        -> tambah mahasiswa          (body JSON)
//   PUT    /api/mahasiswa/{id}   -> ubah seluruh data         (body JSON)
//   PATCH  /api/mahasiswa/{id}   -> ubah sebagian data        (body JSON)
//   DELETE /api/mahasiswa/{id}   -> hapus mahasiswa
//
// Id diambil dari URL (/api/mahasiswa/1). Bentuk lama ?id=1 dari Acara 15
// tetap diterima. Rewrite di public/.htaccess mengubah /api/mahasiswa/1
// menjadi api/mahasiswa.php?id=1.
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

const ALLOWED_FIELDS = ['nim', 'nama', 'email', 'prodi_id', 'angkatan', 'status'];

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
$allowed = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

if (!in_array($method, $allowed, true)) {
    header('Allow: ' . implode(', ', $allowed));
    respond(405, 'Method ' . $method . ' tidak didukung. Gunakan ' . implode(', ', $allowed) . '.');
}

// ---------------------------------------------------------------------
// Id dari URL: harus angka positif. null berarti "tanpa id" (koleksi).
// ---------------------------------------------------------------------
$id = null;

if (isset($_GET['id'])) {
    $rawId = (string) $_GET['id'];

    if (!ctype_digit($rawId) || (int) $rawId < 1) {
        respond(400, 'Id harus berupa angka positif.');
    }

    $id = (int) $rawId;
}

// Kombinasi method + ada/tidaknya id yang tidak berlaku pada REST.
if ($id === null && in_array($method, ['PUT', 'PATCH', 'DELETE'], true)) {
    respond(400, 'Id mahasiswa wajib disertakan pada URL, mis. /api/mahasiswa/1.');
}

if ($id !== null && $method === 'POST') {
    header('Allow: GET, PUT, PATCH, DELETE');
    respond(405, 'POST hanya untuk /api/mahasiswa (tanpa id). Gunakan PUT untuk mengubah data.');
}

try {
    $database = new Database(); // koneksi PDO (Acara 9)
    $service = new MahasiswaService(
        new MahasiswaRepository($database),
        new ProdiRepository($database)
    );

    switch ($method) {
        case 'GET':
            handleGet($service, $id);
            break;
        case 'POST':
            handlePost($service);
            break;
        case 'PUT':
            handleUpdate($service, $id, false);
            break;
        case 'PATCH':
            handleUpdate($service, $id, true);
            break;
        case 'DELETE':
            handleDelete($service, $id);
            break;
    }
} catch (Throwable $e) {
    // Detail teknis hanya masuk log (Acara 14), pengguna API hanya melihat
    // pesan umum.
    Logger::error('API mahasiswa', $e);
    respond(500, 'Terjadi kesalahan pada server.');
}

// ---------------------------------------------------------------------
// GET: seluruh data atau satu data
// ---------------------------------------------------------------------
function handleGet(MahasiswaService $service, ?int $id): void
{
    if ($id === null) {
        respond(200, 'Data berhasil diambil', $service->listForApi());
    }

    $mahasiswa = $service->findForApi($id);

    if ($mahasiswa === null) {
        respond(404, 'Data mahasiswa tidak ditemukan.');
    }

    respond(200, 'Data berhasil diambil', $mahasiswa);
}

// ---------------------------------------------------------------------
// Membaca body JSON. Mengembalikan hanya field yang dikenal, bernilai
// teks/angka. Gagal -> langsung respond 400 / 422.
// ---------------------------------------------------------------------
function readJsonBody(): array
{
    $raw = trim((string) file_get_contents('php://input'));

    if ($raw === '') {
        respond(400, 'Body request kosong. Kirim data dalam format JSON.');
    }

    $json = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($json)) {
        respond(400, 'Format JSON tidak valid.');
    }

    $input = [];
    $errors = [];

    foreach (ALLOWED_FIELDS as $field) {
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

    return $input;
}

// Angkatan bila tidak dikirim: 2 digit awal NIM (23004 -> 2023).
function defaultAngkatan(array $input): string
{
    $nim = (string) ($input['nim'] ?? '');

    return (ctype_digit($nim) && strlen($nim) >= 2)
        ? '20' . substr($nim, 0, 2)
        : date('Y');
}

// Mengubah hasil Service yang gagal menjadi response error.
function respondFailure(array $result): void
{
    if (!empty($result['notFound'])) {
        respond(404, 'Data mahasiswa tidak ditemukan.');
    }

    if (empty($result['errors'])) {
        // Kegagalan database (sudah dicatat ke log oleh Service).
        respond(500, 'Terjadi kesalahan pada server.');
    }

    // 409 Conflict untuk NIM duplikat, 422 untuk kesalahan validasi lain.
    $status = (($result['errors']['nim'] ?? '') === 'NIM sudah terdaftar') ? 409 : 422;

    respond($status, $result['message'], null, $result['errors']);
}

// ---------------------------------------------------------------------
// POST: tambah data. Contoh body:
//   { "nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com",
//     "prodi_id": 1 }
// ---------------------------------------------------------------------
function handlePost(MahasiswaService $service): void
{
    $input = readJsonBody();
    $input['status'] = $input['status'] ?? 'aktif';

    if (!array_key_exists('angkatan', $input)) {
        $input['angkatan'] = defaultAngkatan($input);
    }

    $result = $service->create($input);

    if (!$result['success']) {
        respondFailure($result);
    }

    header('Location: ' . BASE_URL . '/api/mahasiswa/' . $result['id']);
    respond(201, 'Data mahasiswa berhasil ditambahkan', $service->findForApi($result['id']));
}

// ---------------------------------------------------------------------
// PUT   : mengganti seluruh data (nim, nama, email, prodi_id wajib).
// PATCH : hanya field yang dikirim yang diubah, sisanya tetap.
// ---------------------------------------------------------------------
function handleUpdate(MahasiswaService $service, int $id, bool $partial): void
{
    $current = $service->findForApi($id);

    // Cek id lebih dulu: id yang tidak ada selalu 404, apa pun isi body.
    if ($current === null) {
        respond(404, 'Data mahasiswa tidak ditemukan.');
    }

    $input = readJsonBody();

    if ($partial) {
        if (empty($input)) {
            respond(400, 'Body PATCH tidak berisi field yang dikenal.');
        }

        // Gabungkan dengan data yang ada.
        $input = array_merge([
            'nim'      => $current['nim'],
            'nama'     => $current['nama'],
            'email'    => $current['email'],
            'prodi_id' => $current['prodi_id'],
            'angkatan' => $current['angkatan'],
            'status'   => $current['status'],
        ], $input);
    } else {
        $input['status'] = $input['status'] ?? 'aktif';

        if (!array_key_exists('angkatan', $input)) {
            $input['angkatan'] = defaultAngkatan($input);
        }
    }

    $result = $service->updateById($id, $input);

    if (!$result['success']) {
        respondFailure($result);
    }

    respond(200, 'Data mahasiswa berhasil diubah', $service->findForApi($id));
}

// ---------------------------------------------------------------------
// DELETE: hapus data berdasarkan id
// ---------------------------------------------------------------------
function handleDelete(MahasiswaService $service, int $id): void
{
    $result = $service->deleteById($id);

    if (!$result['success']) {
        respondFailure($result);
    }

    respond(200, 'Data mahasiswa berhasil dihapus', null);
}
