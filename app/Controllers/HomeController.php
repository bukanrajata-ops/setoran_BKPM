<?php

namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->view('dashboard/index', [], 'dashboard');
    }
}
