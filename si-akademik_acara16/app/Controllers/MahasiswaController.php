<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Services\MahasiswaService;

// Acara 13: Controller dibuat "tipis". Tugasnya hanya menerima request,
// memanggil MahasiswaService, lalu menentukan response (view / redirect /
// flash message). Validasi dan logika bisnis ada di MahasiswaService,
// query database ada di Repository.
class MahasiswaController extends BaseController
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $keyword = trim($_GET['q'] ?? '');

        $this->view(
            'mahasiswa/index',
            [
                'daftarMahasiswa' => $this->service->search($keyword),
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
                'prodiList' => $this->service->prodiOptions(),
                'errors' => [],
                'old' => [],
            ],
            'mahasiswa'
        );
    }

    public function store(): void
    {
        $result = $this->service->create($_POST);

        if ($result['success']) {
            $this->flash('success', $result['message']);
            $this->redirect('/mahasiswa');
        }

        // Gagal: tampilkan flash (sekali tampil) dan kembalikan ke form
        // beserta pesan error per field dan isian sebelumnya.
        $this->flash('danger', $result['message']);

        $this->view(
            'mahasiswa/create',
            [
                'prodiList' => $this->service->prodiOptions(),
                'errors' => $result['errors'],
                'old' => $result['data'],
            ],
            'mahasiswa'
        );
    }

    public function edit($id): void
    {
        $data = $this->service->find((string) $id);

        if (!$data) {
            $this->flash('danger', 'Data mahasiswa tidak ditemukan.');
            $this->redirect('/mahasiswa');
        }

        $this->view(
            'mahasiswa/edit',
            [
                'mahasiswa' => $data,
                'originalNim' => $id,
                'prodiList' => $this->service->prodiOptions(),
                'errors' => [],
            ],
            'mahasiswa'
        );
    }

    public function update($id): void
    {
        $result = $this->service->update((string) $id, $_POST);

        if ($result['success']) {
            $this->flash('success', $result['message']);
            $this->redirect('/mahasiswa');
        }

        $this->flash('danger', $result['message']);

        if (!empty($result['notFound'])) {
            $this->redirect('/mahasiswa');
        }

        $this->view(
            'mahasiswa/edit',
            [
                'mahasiswa' => $result['data'],
                'originalNim' => $id,
                'prodiList' => $this->service->prodiOptions(),
                'errors' => $result['errors'],
            ],
            'mahasiswa'
        );
    }

    public function delete($id): void
    {
        $result = $this->service->delete((string) $id);

        $this->flash($result['success'] ? 'success' : 'danger', $result['message']);
        $this->redirect('/mahasiswa');
    }

    public function show($nim): void
    {
        $this->view(
            'mahasiswa/detail',
            ['mahasiswaDitemukan' => $this->service->detail((string) $nim)],
            'mahasiswa'
        );
    }
}
