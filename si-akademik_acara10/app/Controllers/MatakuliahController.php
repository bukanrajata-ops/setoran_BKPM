<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Matakuliah;
use App\Models\Prodi;
use PDOException;

class MatakuliahController extends BaseController
{
    public function index(): void
    {
        $this->view(
            'matakuliah/index',
            ['matakuliahList' => Matakuliah::all()],
            'matakuliah'
        );
    }

    public function create(): void
    {
        $this->view(
            'matakuliah/create',
            ['prodiList' => Prodi::all(), 'error' => null],
            'matakuliah'
        );
    }

    public function store(): void
    {
        $kode = strtoupper(trim($_POST['kode'] ?? ''));
        $nama = trim($_POST['nama'] ?? '');
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);

        if ($kode === '' || $nama === '' || $sks <= 0 || $prodiId <= 0) {
            $this->view(
                'matakuliah/create',
                ['prodiList' => Prodi::all(), 'error' => 'Semua data wajib diisi dengan benar.'],
                'matakuliah'
            );
            return;
        }

        try {
            Matakuliah::create($kode, $nama, $sks, $prodiId);
            $this->flash('success', 'Mata kuliah berhasil ditambahkan.');
            $this->redirect('/matakuliah');
        } catch (PDOException $e) {
            $this->view(
                'matakuliah/create',
                ['prodiList' => Prodi::all(), 'error' => 'Kode mata kuliah sudah digunakan.'],
                'matakuliah'
            );
        }
    }

    public function edit($id): void
    {
        $data = Matakuliah::find((int) $id);

        if (!$data) {
            $this->flash('danger', 'Mata kuliah tidak ditemukan.');
            $this->redirect('/matakuliah');
        }

        $this->view(
            'matakuliah/edit',
            ['matakuliah' => $data, 'prodiList' => Prodi::all(), 'error' => null],
            'matakuliah'
        );
    }

    public function update($id): void
    {
        $kode = strtoupper(trim($_POST['kode'] ?? ''));
        $nama = trim($_POST['nama'] ?? '');
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);

        if ($kode === '' || $nama === '' || $sks <= 0 || $prodiId <= 0) {
            $this->flash('danger', 'Semua data wajib diisi dengan benar.');
            $this->redirect('/matakuliah/' . $id . '/edit');
        }

        try {
            Matakuliah::update((int) $id, $kode, $nama, $sks, $prodiId);
            $this->flash('success', 'Mata kuliah berhasil diubah.');
        } catch (PDOException $e) {
            $this->flash('danger', 'Gagal mengubah mata kuliah.');
        }

        $this->redirect('/matakuliah');
    }

    public function delete($id): void
    {
        try {
            Matakuliah::delete((int) $id);
            $this->flash('success', 'Mata kuliah berhasil dihapus.');
        } catch (PDOException $e) {
            $this->flash('danger', 'Mata kuliah gagal dihapus.');
        }

        $this->redirect('/matakuliah');
    }
}
