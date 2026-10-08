<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Dosen;

class DosenController extends BaseController
{
    public function index(): void
    {
        $this->view('dosen/index', ['daftarDosen' => Dosen::getAll()], 'dosen');
    }
}
