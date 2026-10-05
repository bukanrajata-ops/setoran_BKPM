<?php
/**
 * app/Models/Mahasiswa.php
 * Model bertanggung jawab atas data mahasiswa.
 * Belum tersambung ke database, data disimpan langsung di sini
 * (akan diganti dengan query MySQL pada acara berikutnya).
 */

class Mahasiswa
{
    /**
     * Data dasar (bawaan) mahasiswa.
     */
    private array $data = [
        ['nim' => '23001', 'nama' => 'Andi',   'prodi' => 'Teknik Informatika'],
        ['nim' => '23002', 'nama' => 'Budi',   'prodi' => 'Teknik Informatika'],
        ['nim' => '23003', 'nama' => 'Citra',  'prodi' => 'Sistem Informasi'],
        ['nim' => '23004', 'nama' => 'Dinda',  'prodi' => 'Teknik Informatika'],
        ['nim' => '23005', 'nama' => 'Waluyo', 'prodi' => 'Teknik Informatika'],
        ['nim' => '23010', 'nama' => 'Asep',   'prodi' => 'Sistem Informasi'],
    ];

    public function __construct()
    {
        // Data yang ditambahkan lewat form "Tambah Mahasiswa" disimpan sementara di session
        if (!isset($_SESSION['mahasiswa_tambahan'])) {
            $_SESSION['mahasiswa_tambahan'] = [];
        }
    }

    /**
     * Mengambil semua data mahasiswa (data dasar + data tambahan dari form).
     */
    public function getAll(): array
    {
        return array_merge($this->data, $_SESSION['mahasiswa_tambahan']);
    }

    /**
     * Mengambil satu data mahasiswa berdasarkan NIM.
     */
    public function getByNim(string $nim): ?array
    {
        foreach ($this->getAll() as $mhs) {
            if ($mhs['nim'] === $nim) {
                return $mhs;
            }
        }
        return null;
    }

    /**
     * Validasi keunikan NIM (contoh tanggung jawab Model terhadap aturan bisnis).
     */
    public function nimSudahAda(string $nim): bool
    {
        return $this->getByNim($nim) !== null;
    }

    /**
     * Menyimpan data mahasiswa baru.
     */
    public function simpan(string $nim, string $nama, string $prodi): bool
    {
        if ($this->nimSudahAda($nim)) {
            return false; // NIM tidak boleh duplikat
        }

        $_SESSION['mahasiswa_tambahan'][] = [
            'nim'   => $nim,
            'nama'  => $nama,
            'prodi' => $prodi,
        ];

        return true;
    }
}
