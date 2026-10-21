<?php
// Tugas Praktik Mandiri 1: Controller untuk resource kedua (Buku), pola sama dengan MahasiswaController.
class BukuController {
    private Buku $model;

    public function __construct() {
        $this->model = new Buku();
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
        if (empty($data['judul']) || empty($data['penulis']) || empty($data['tahun'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Field judul, penulis, dan tahun wajib diisi']);
            exit;
        }
        $id = $this->model->create($data);
        http_response_code(201);
        echo json_encode(['success' => true, 'message' => 'Data berhasil ditambahkan',
            'data' => ['id' => $id]]);
    }

    public function update($id): void {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->update((int) $id, $data);
        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    public function destroy($id): void {
        header('Content-Type: application/json');
        $this->model->delete((int) $id);
        echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
