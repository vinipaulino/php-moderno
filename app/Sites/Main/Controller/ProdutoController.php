<?php

namespace App\Sites\Main\Controller;

class ProdutoController
{
    public function __construct()
    {
    }

    public function index()
    {
        echo 'Index main';
    }

    public function listar()
    {
        dd($_GET['fb']);
        echo 'Listar main';
    }
}
