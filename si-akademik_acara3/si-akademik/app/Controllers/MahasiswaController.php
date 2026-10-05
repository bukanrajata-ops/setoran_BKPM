<?php
/**
 * app/Controllers/MahasiswaController.php
 * Controller mengatur alur: menerima request, memanggil Model,
 * lalu menentukan View yang ditampilkan.
 */

require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaController
{
    private Mahasiswa $model;

    public function __construct()
    {
        $this->model = new Mahasiswa();
    }

    /**
     * Menampilkan daftar seluruh mahasiswa.
     */
    public function index(): void
    {
        $mahasiswa = $this->model->getAll();
        require __DIR__ . '/../Views/mahasiswa/index.php';
    }

    /**
     * Menampilkan detail satu mahasiswa berdasarkan NIM dari parameter URL.
     * Contoh: ?url=mahasiswa/detail&nim=23002
     */
    public function detail(): void
    {
        $nim = $_GET['nim'] ?? null;
        $mahasiswa = $nim ? $this->model->getByNim($nim) : null;
        require __DIR__ . '/../Views/mahasiswa/detail.php';
    }

    /**
     * Menampilkan form tambah mahasiswa.
     */
    public function create(): void
    {
        $error = null;
        require __DIR__ . '/../Views/mahasiswa/create.php';
    }

    /**
     * Memproses data form tambah mahasiswa (method POST).
     */
    public function store(): void
    {
        $nim   = trim($_POST['nim'] ?? '');
        $nama  = trim($_POST['nama'] ?? '');
        $prodi = trim($_POST['prodi'] ?? '');

        if ($nim === '' || $nama === '' || $prodi === '') {
            $error = 'Semua field wajib diisi.';
            require __DIR__ . '/../Views/mahasiswa/create.php';
            return;
        }

        $berhasil = $this->model->simpan($nim, $nama, $prodi);

        if (!$berhasil) {
            $error = "NIM {$nim} sudah terdaftar.";
            require __DIR__ . '/../Views/mahasiswa/create.php';
            return;
        }

        header('Location: ?url=mahasiswa');
        exit;
    }
}
