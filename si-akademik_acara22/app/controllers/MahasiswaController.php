<?php
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    public function index(): void {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $data = $this->model->find((int) $id);
            if (!$data) Response::json(404, false, 'Data tidak ditemukan');
            Response::json(200, true, 'Data berhasil diambil', $data);
        }
        Response::json(200, true, 'Data berhasil diambil', $this->model->getAll());
    }

    public function store(): void {
        Auth::requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        // 400: field wajib kosong (errors berisi pesan per field)
        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (empty($data[$field])) $errors[$field] = "Field $field wajib diisi";
        }
        if ($errors) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi', null, $errors);
        }

        // 422: format NIM salah
        if (strlen($data['nim']) !== 9) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null,
                ['nim' => 'NIM harus 9 digit']);
        }

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            // Validasi Model: NIM duplikat
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void {
        Auth::requireAuth();
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['nim']) && strlen($data['nim']) !== 9) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null,
                ['nim' => 'NIM harus 9 digit']);
        }

        try {
            $this->model->update((int) $id, $data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void {
        Auth::requireAuth();
        $this->model->delete((int) $id);
        Response::json(200, true, 'Data berhasil dihapus');
    }
}
