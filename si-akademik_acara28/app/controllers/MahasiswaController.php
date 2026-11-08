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
        Auth::requireAdmin();
        $data = $this->ambilInput();
        $this->validasiData($data);
        $fotoBaru = $this->prosesFoto();             // 422 bila berkas tidak valid; null bila tidak ada berkas
        if ($fotoBaru !== null) $data['foto'] = $fotoBaru;

        try {
            $id = $this->model->create($data);
        } catch (DomainException $e) {
            // Validasi Model: NIM duplikat. Berkas yang sudah dipindahkan dihapus agar tidak yatim.
            $this->hapusBerkasFoto($fotoBaru);
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }
        Response::json(201, true, 'Data berhasil ditambahkan', ['id' => $id]);
    }

    public function update($id): void {
        Auth::requireAdmin();
        $this->requireId($id);                       // 400: parameter id wajib
        $lama = $this->model->find((int) $id);
        if (!$lama) {                                // 404: data dengan id tersebut tidak ada
            Response::json(404, false, 'Data tidak ditemukan');
        }
        $data = $this->ambilInput();
        $this->validasiData($data);
        $fotoBaru = $this->prosesFoto();
        if ($fotoBaru !== null) $data['foto'] = $fotoBaru;

        try {
            $this->model->update((int) $id, $data);
        } catch (DomainException $e) {
            $this->hapusBerkasFoto($fotoBaru);
            Response::json(409, false, $e->getMessage(), null, ['nim' => $e->getMessage()]);
        }
        // Tugas mandiri 1: foto lama dihapus (unlink) setelah data berhasil diperbarui dengan foto baru
        if ($fotoBaru !== null) $this->hapusBerkasFoto($lama['foto'] ?? null);
        Response::json(200, true, 'Data berhasil diperbarui');
    }

    public function destroy($id): void {
        Auth::requireAdmin();
        $this->requireId($id);                       // 400: parameter id wajib
        $lama = $this->model->find((int) $id);
        if (!$lama) {                                // 404: data dengan id tersebut tidak ada
            Response::json(404, false, 'Data tidak ditemukan');
        }
        $this->model->delete((int) $id);
        $this->hapusBerkasFoto($lama['foto'] ?? null);
        Response::json(200, true, 'Data berhasil dihapus');
    }

    // Body dapat berupa multipart/form-data (ada berkas) atau JSON (seperti sebelumnya)
    private function ambilInput(): array {
        $tipe = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($tipe, 'multipart/form-data') === 0) {
            return $_POST;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        return is_array($data) ? $data : [];
    }

    // 400: field wajib kosong; 422: format NIM salah (dipakai bersama oleh store dan update)
    private function validasiData(array $data): void {
        $errors = [];
        foreach (['nim', 'nama', 'prodi'] as $field) {
            if (empty($data[$field])) $errors[$field] = "Field $field wajib diisi";
        }
        if ($errors) {
            Response::json(400, false, 'Field nim, nama, dan prodi wajib diisi', null, $errors);
        }
        if (strlen($data['nim']) !== 9) {
            Response::json(422, false, 'Format NIM tidak valid, harus 9 digit', null,
                ['nim' => 'NIM harus 9 digit']);
        }
    }

    private function folderUpload(): string {
        // Acara 28: pada hosting uploads/ berada di htdocs/uploads (dua tingkat di atas controllers/)
        $hosting = __DIR__ . '/../../uploads/';
        return is_dir($hosting) ? $hosting : __DIR__ . '/../../public/uploads/';
    }

    // Memvalidasi dan menyimpan foto. Mengembalikan nama berkas baru, atau null bila tidak ada berkas.
    private function prosesFoto(): ?string {
        $file = $_FILES['foto'] ?? null;
        // Form multipart tanpa berkas tetap menghasilkan $_FILES['foto'] dengan error UPLOAD_ERR_NO_FILE,
        // sehingga !empty($_FILES['foto']) saja tidak cukup untuk menandai adanya berkas.
        if ($file === null || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $pesanGagal = ['foto' => 'Foto tidak dapat diunggah'];
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
            || $file['size'] > 2 * 1024 * 1024) {
            Response::json(422, false, 'Ukuran berkas maksimal 2MB', null, ['foto' => 'Ukuran berkas maksimal 2MB']);
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Response::json(422, false, 'Berkas gagal diunggah, coba lagi', null, $pesanGagal);
        }

        // Ekstensi pada nama berkas dan signature berkas yang sesungguhnya harus sama-sama JPG/PNG
        $tipe = @exif_imagetype($file['tmp_name']);
        $peta = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png'];
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ekstensi === 'jpeg') $ekstensi = 'jpg';
        if (!isset($peta[$tipe]) || $peta[$tipe] !== $ekstensi) {
            Response::json(422, false, 'Berkas harus berupa gambar JPG/PNG yang valid', null,
                ['foto' => 'Berkas harus berupa gambar JPG/PNG yang valid']);
        }

        // Nama asli dari pengguna tidak dipakai: nama baru unik, ekstensi dari signature berkas
        $namaBaru = uniqid('foto_', true) . '.' . $peta[$tipe];
        if (!move_uploaded_file($file['tmp_name'], $this->folderUpload() . $namaBaru)) {
            Response::json(500, false, 'Terjadi kesalahan pada server');
        }
        return $namaBaru;
    }

    // basename() mencegah nama berkas dari database dipakai untuk keluar dari folder uploads
    private function hapusBerkasFoto(?string $nama): void {
        if (!$nama) return;
        $path = $this->folderUpload() . basename($nama);
        if (is_file($path)) unlink($path);
    }

    // Parameter id wajib berupa bilangan bulat positif
    private function requireId($id): void {
        if ($id === null || !ctype_digit((string) $id) || (int) $id < 1) {
            Response::json(400, false, 'Parameter id wajib diisi', null,
                ['id' => 'Parameter id harus berupa bilangan bulat positif']);
        }
    }
}
