<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Prodi;
use PDOException;

class ProdiController extends BaseController
{
    public function index(): void
    {
        $this->view('prodi/index', ['prodiList' => Prodi::all()], 'prodi');
    }

    public function create(): void
    {
        $this->view('prodi/create', ['error' => null], 'prodi');
    }

    public function store(): void
    {
        $kode = strtoupper(trim($_POST['kode'] ?? ''));
        $nama = trim($_POST['nama'] ?? '');

        if ($kode === '' || $nama === '') {
            $this->view('prodi/create', ['error' => 'Kode dan nama wajib diisi.'], 'prodi');
            return;
        }

        try {
            Prodi::create($kode, $nama);
            $this->flash('success', 'Data prodi berhasil ditambahkan.');
            $this->redirect('/prodi');
        } catch (PDOException $e) {
            $this->view('prodi/create', ['error' => 'Kode prodi sudah digunakan.'], 'prodi');
        }
    }

    public function edit($id): void
    {
        $data = Prodi::find((int) $id);

        if (!$data) {
            $this->flash('danger', 'Prodi tidak ditemukan.');
            $this->redirect('/prodi');
        }

        $this->view('prodi/edit', ['prodi' => $data, 'error' => null], 'prodi');
    }

    public function update($id): void
    {
        $kode = strtoupper(trim($_POST['kode'] ?? ''));
        $nama = trim($_POST['nama'] ?? '');

        if ($kode === '' || $nama === '') {
            $this->flash('danger', 'Kode dan nama wajib diisi.');
            $this->redirect('/prodi/' . $id . '/edit');
        }

        try {
            Prodi::update((int) $id, $kode, $nama);
            $this->flash('success', 'Data prodi berhasil diubah.');
        } catch (PDOException $e) {
            $this->flash('danger', 'Gagal mengubah prodi.');
        }

        $this->redirect('/prodi');
    }

    public function delete($id): void
    {
        try {
            Prodi::delete((int) $id);
            $this->flash('success', 'Data prodi berhasil dihapus.');
        } catch (PDOException $e) {
            $this->flash('danger', 'Prodi tidak bisa dihapus karena masih digunakan mahasiswa/mata kuliah.');
        }

        $this->redirect('/prodi');
    }
}
