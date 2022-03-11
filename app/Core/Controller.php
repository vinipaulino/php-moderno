<?php

namespace App\Core;

class Controller
{
    private string $base;
    private string $area;

    public function __construct(string $area = 'Main')
    {
        $this->$area = $area;

        $this->base = "../app/Sites/{$this->$area}/View/";
    }

    protected function view(string $page, array $params = [])
    {
        $page = str_replace('.', '/', $page) . ".twig";

        $loader = new \Twig\Loader\FilesystemLoader($this->base);

        $twig = new \Twig\Environment($loader, [
            //'cache' => '../cache/',
        ]);

        echo $twig->render($page, $params);
    }
}
