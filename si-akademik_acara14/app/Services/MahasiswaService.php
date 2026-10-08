<?php

namespace App\Services;

use App\Core\Logger;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use PDOException;

// Acara 13 - Service Layer.
// Controller  : menerima request, memanggil Service, menentukan response.
// Service     : validasi + logika bisnis (class ini).
// Repository  : menjalankan query ke database.
//
// Setiap method create/update/delete mengembalikan array dengan bentuk:
//   success (bool)  : proses berhasil atau tidak
//   message (string): teks untuk flash message
//   errors  (array) : pesan error per field, mis. ['nim' => 'NIM sudah terdaftar']
//   data    (array) : input yang sudah dirapikan (untuk mengisi ulang form)
class MahasiswaService
{
    private const STATUS_VALID = ['aktif', 'cuti', 'lulus'];

    public function __construct(
        private MahasiswaRepository $repo,
        private ProdiRepository $prodiRepo
    ) {
    }

    // ---------------------------------------------------------------
    // Pembacaan data (diteruskan ke Repository agar Controller tidak
    // perlu mengenal Repository sama sekali)
    // ---------------------------------------------------------------

    public function search(string $keyword = ''): array
    {
        return $this->repo->all($keyword);
    }

    public function find(string $nim): ?array
    {
        return $this->repo->findByNimData($nim);
    }

    public function detail(string $nim)
    {
        return $this->repo->findByNim($nim);
    }

    public function prodiOptions(): array
    {
        return $this->prodiRepo->all();
    }

    // ---------------------------------------------------------------
    // Tambah data
    // ---------------------------------------------------------------

    public function create(array $input): array
    {
        $data = $this->normalize($input);
        $errors = $this->validate($data);

        if (!empty($errors)) {
            return $this->failed($errors, $data);
        }

        try {
            $id = $this->repo->create($data);
        } catch (PDOException $e) {
            Logger::error('Gagal membuat data mahasiswa (nim=' . $data['nim'] . ')', $e);
            return $this->databaseFailed($e, $data);
        }

        return [
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan.',
            'errors'  => [],
            'data'    => $data,
            'id'      => $id,
        ];
    }

    // ---------------------------------------------------------------
    // Ubah data
    // ---------------------------------------------------------------

    public function update(string $oldNim, array $input): array
    {
        $data = $this->normalize($input);

        if ($this->repo->findByNimData($oldNim) === null) {
            return [
                'success'  => false,
                'message'  => 'Data mahasiswa tidak ditemukan.',
                'errors'   => [],
                'data'     => $data,
                'notFound' => true,
            ];
        }

        // NIM milik data ini sendiri tidak dihitung sebagai duplikat.
        $errors = $this->validate($data, $oldNim);

        if (!empty($errors)) {
            return $this->failed($errors, $data);
        }

        try {
            $this->repo->updateByNim($oldNim, $data);
        } catch (PDOException $e) {
            Logger::error('Gagal mengubah data mahasiswa (nim=' . $oldNim . ')', $e);
            return $this->databaseFailed($e, $data);
        }

        return [
            'success' => true,
            'message' => 'Data mahasiswa berhasil diubah.',
            'errors'  => [],
            'data'    => $data,
        ];
    }

    // ---------------------------------------------------------------
    // Hapus data
    // ---------------------------------------------------------------

    public function delete(string $nim): array
    {
        try {
            $this->repo->deleteByNim($nim);
        } catch (PDOException $e) {
            Logger::error('Gagal menghapus data mahasiswa (nim=' . $nim . ')', $e);
            return [
                'success' => false,
                'message' => 'Data mahasiswa gagal dihapus.',
                'errors'  => [],
                'data'    => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Data mahasiswa berhasil dihapus.',
            'errors'  => [],
            'data'    => [],
        ];
    }

    // ---------------------------------------------------------------
    // Validasi & logika bisnis
    // ---------------------------------------------------------------

    private function normalize(array $input): array
    {
        return [
            'nim'      => trim((string) ($input['nim'] ?? '')),
            'nama'     => trim((string) ($input['nama'] ?? '')),
            'email'    => trim((string) ($input['email'] ?? '')),
            'prodi_id' => (int) ($input['prodi_id'] ?? 0),
            'angkatan' => trim((string) ($input['angkatan'] ?? '')),
            'status'   => strtolower(trim((string) ($input['status'] ?? 'aktif'))),
        ];
    }

    /**
     * @param string|null $exceptNim NIM lama saat update (dikecualikan dari
     *                               pemeriksaan "NIM sudah terdaftar").
     */
    private function validate(array $data, ?string $exceptNim = null): array
    {
        $errors = [];

        if ($data['nim'] === '') {
            $errors['nim'] = 'NIM wajib diisi';
        } elseif (!ctype_digit($data['nim'])) {
            $errors['nim'] = 'NIM harus berupa angka';
        } elseif ($this->repo->existsByNim($data['nim'], $exceptNim)) {
            $errors['nim'] = 'NIM sudah terdaftar';
        }

        if ($data['nama'] === '') {
            $errors['nama'] = 'Nama wajib diisi';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'Email wajib diisi';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email tidak valid';
        }

        if ($data['prodi_id'] <= 0) {
            $errors['prodi_id'] = 'Program studi wajib dipilih';
        } elseif (!$this->prodiRepo->existsById($data['prodi_id'])) {
            $errors['prodi_id'] = 'Program studi tidak tersedia';
        }

        if (!preg_match('/^\d{4}$/', $data['angkatan'])) {
            $errors['angkatan'] = 'Angkatan harus terdiri dari 4 angka';
        }

        if (!in_array($data['status'], self::STATUS_VALID, true)) {
            $errors['status'] = 'Status mahasiswa tidak valid';
        }

        return $errors;
    }

    private function failed(array $errors, array $data): array
    {
        // Pesan flash dibuat spesifik untuk kasus NIM duplikat.
        $message = (($errors['nim'] ?? '') === 'NIM sudah terdaftar')
            ? 'NIM sudah terdaftar.'
            : 'Data gagal disimpan. Periksa kembali isian Anda.';

        return [
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
            'data'    => $data,
        ];
    }

    private function databaseFailed(PDOException $e, array $data): array
    {
        // 23000 = pelanggaran constraint (mis. NIM unik) pada kondisi balapan,
        // yaitu NIM diambil orang lain setelah pemeriksaan existsByNim().
        if ($e->getCode() === '23000') {
            return $this->failed(['nim' => 'NIM sudah terdaftar'], $data);
        }

        return [
            'success' => false,
            'message' => 'Data gagal disimpan karena kesalahan database.',
            'errors'  => [],
            'data'    => $data,
        ];
    }
}
