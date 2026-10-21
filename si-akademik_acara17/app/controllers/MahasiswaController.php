<?php
class MahasiswaController {
    private Mahasiswa $model;

    public function __construct() {
        $this->model = new Mahasiswa();
    }

    public function index(): void {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;
        if ($id) {
            $data = $this->model->find((int) $id);
            if (!$data) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
                exit;
            }
            echo json_encode(['success' => true, 'data' => $data]);
            exit;
        }
        echo json_encode(['success' => true, 'data' => $this->model->getAll()]);
    }

    public function store(): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nim']) || empty($data['nama']) || empty($data['prodi'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Field nim, nama, dan prodi wajib diisi']);
            exit;
        }
        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            // Validasi Model (Tugas Praktik Mandiri 2): NIM duplikat
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Data berhasil ditambahkan',
            'data' => ['id' => $id]]);
    }

    public function update($id): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $this->model->update((int) $id, $data);
        } catch (DomainException $e) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id): void {
        header('Content-Type: application/json');
        $this->model->delete((int) $id);
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
