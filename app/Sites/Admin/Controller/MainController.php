<?php

namespace App\Sites\Admin\Controller;

use App\Core\Controller;

class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }
    public function index()
    {
        $this->view('home.index');
    }
}
