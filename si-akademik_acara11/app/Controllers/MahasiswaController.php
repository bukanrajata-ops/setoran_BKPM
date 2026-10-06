<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Repositories\MahasiswaRepository;
use InvalidArgumentException;
use PDOException;

// Acara 10: mewarisi BaseController (view, redirect, flash) dan seluruh
// akses data dilakukan lewat MahasiswaRepository (tanpa SQL di controller).
class MahasiswaController extends BaseController
{
    private MahasiswaRepository $repo;

    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');

        $this->view(
            'mahasiswa/index',
            [
                'daftarMahasiswa' => $this->repo->all($keyword),
                'keyword' => $keyword,
            ],
            'mahasiswa'
        );
    }

    public function create(): void
    {
        $this->view(
            'mahasiswa/create',
            [
                'prodiList' => Prodi::all(),
                'error' => null,
            ],
            'mahasiswa'
        );
    }

    public function store(): void
    {
        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);
        $angkatan = (int) ($_POST['angkatan'] ?? date('Y'));
        $status = $_POST['status'] ?? 'aktif';

        try {
            $mahasiswa = new Mahasiswa($nim, $nama, '');
            $mahasiswa->setNim($nim);
            $mahasiswa->setNama($nama);
            $mahasiswa->setAngkatan((string) $angkatan);
            $mahasiswa->setStatus($status);

            if ($email === '' || $prodiId <= 0) {
                throw new InvalidArgumentException('Email dan prodi wajib diisi.');
            }

            $this->repo->create(
                $mahasiswa->getNim(),
                $mahasiswa->getNama(),
                $email,
                $prodiId,
                (int) $mahasiswa->getAngkatan(),
                $mahasiswa->getStatus()
            );

            $this->flash('success', 'Data mahasiswa berhasil ditambahkan.');
            $this->redirect('/mahasiswa');
        } catch (InvalidArgumentException $e) {
            $this->view(
                'mahasiswa/create',
                ['prodiList' => Prodi::all(), 'error' => $e->getMessage()],
                'mahasiswa'
            );
        } catch (PDOException $e) {
            $this->view(
                'mahasiswa/create',
                [
                    'prodiList' => Prodi::all(),
                    'error' => 'Gagal menyimpan. Pastikan NIM belum digunakan.',
                ],
                'mahasiswa'
            );
        }
    }

    public function edit($id): void
    {
        $data = $this->repo->findByNimData((string) $id);

        if (!$data) {
            $this->flash('danger', 'Data mahasiswa tidak ditemukan.');
            $this->redirect('/mahasiswa');
        }

        $this->view(
            'mahasiswa/edit',
            [
                'mahasiswa' => $data,
                'originalNim' => $id,
                'prodiList' => Prodi::all(),
                'error' => null,
            ],
            'mahasiswa'
        );
    }

    public function update($id): void
    {
        $oldNim = (string) $id;
        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);
        $angkatan = (int) ($_POST['angkatan'] ?? date('Y'));
        $status = $_POST['status'] ?? 'aktif';

        try {
            $mahasiswa = new Mahasiswa($nim, $nama, '');
            $mahasiswa->setNim($nim);
            $mahasiswa->setNama($nama);
            $mahasiswa->setAngkatan((string) $angkatan);
            $mahasiswa->setStatus($status);

            if ($email === '' || $prodiId <= 0) {
                throw new InvalidArgumentException('Email dan prodi wajib diisi.');
            }

            $this->repo->updateByNim(
                $oldNim,
                $mahasiswa->getNim(),
                $mahasiswa->getNama(),
                $email,
                $prodiId,
                (int) $mahasiswa->getAngkatan(),
                $mahasiswa->getStatus()
            );

            $this->flash('success', 'Data mahasiswa berhasil diubah.');
            $this->redirect('/mahasiswa');
        } catch (InvalidArgumentException $e) {
            $this->view(
                'mahasiswa/edit',
                [
                    'mahasiswa' => array_merge(
                        $this->repo->findByNimData($oldNim) ?? [],
                        $_POST
                    ),
                    'originalNim' => $oldNim,
                    'prodiList' => Prodi::all(),
                    'error' => $e->getMessage(),
                ],
                'mahasiswa'
            );
        } catch (PDOException $e) {
            $this->flash('danger', 'Gagal mengubah data. Pastikan NIM unik.');
            $this->redirect('/mahasiswa');
        }
    }

    public function delete($id): void
    {
        try {
            $this->repo->deleteByNim((string) $id);
            $this->flash('success', 'Data mahasiswa berhasil dihapus.');
        } catch (PDOException $e) {
            $this->flash('danger', 'Data mahasiswa gagal dihapus.');
        }

        $this->redirect('/mahasiswa');
    }

    public function show($nim): void
    {
        $this->view(
            'mahasiswa/detail',
            ['mahasiswaDitemukan' => $this->repo->findByNim($nim)],
            'mahasiswa'
        );
    }
}
