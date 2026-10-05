<?php
/**
 * app/Controllers/DosenController.php
 */

require_once __DIR__ . '/../Models/Dosen.php';

class DosenController
{
    private Dosen $model;

    public function __construct()
    {
        $this->model = new Dosen();
    }

    /**
     * Menampilkan daftar seluruh dosen.
     */
    public function index(): void
    {
        $dosen = $this->model->getAll();
        require __DIR__ . '/../Views/dosen/index.php';
    }
}
