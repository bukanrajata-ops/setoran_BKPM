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
        $this->requireId($id);                       // 400: parameter id wajib
        if (!$this->model->find((int) $id)) {        // 404: data dengan id tersebut tidak ada
            Response::json(404, false, 'Data tidak ditemukan');
        }
        $data = json_decode(file_get_contents('php://input'), true);

        // 400: field wajib kosong (sama seperti store)
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
            $this->model->update((int) $id, $data);
        } catch (DomainException $e) {
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void {
        Auth::requireAuth();
        $this->requireId($id);                       // 400: parameter id wajib
        if (!$this->model->find((int) $id)) {        // 404: data dengan id tersebut tidak ada
            Response::json(404, false, 'Data tidak ditemukan');
        }
        $this->model->delete((int) $id);
        Response::json(200, true, 'Data berhasil dihapus');
    }

    // Parameter id wajib berupa bilangan bulat positif
    private function requireId($id): void {
        if ($id === null || !ctype_digit((string) $id) || (int) $id < 1) {
            Response::json(400, false, 'Parameter id wajib diisi', null,
                ['id' => 'Parameter id harus berupa bilangan bulat positif']);
        }
    }
}
