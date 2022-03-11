<?php

namespace App\Sites\Main\Controller;

use App\Core\Controller;

class MainController extends Controller
{
    public function index()
    {
        $this->view('main.index', [
            'frutas' => [
                'Abacaxi',
                'Banana',
                'Goiaba',
                'Manga',
                'Uva'
            ]
        ]);
    }
}
