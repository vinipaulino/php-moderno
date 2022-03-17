<?php

namespace App\Core;

use App\Classes\Session;

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

        $twig->addGlobal('BASE', BASE);
        $twig->addGlobal('ADMIN_BASE', ADMIN_BASE);

        $twig->addGlobal('username', $_COOKIE['username'] ?? "Usuário");
        $twig->addGlobal('permission', Session::get('permission'));

        echo $twig->render($page, $params);
    }


    /**
     * Carrega a página de mensagem genérica da aplicação
     *
     * @param  mixed $title Título da mensagem
     * @param  mixed $description Descrição da mensagem
     * @param  mixed $link Link para retornar
     * @return void
     */
    protected function showMessage(string $title, string $description, string $link = "#", int $httpCode = 200)
    {
        http_response_code($httpCode);

        $this->view('partials.message', [
            'messageTitle' => $title,
            'messageDescription' => $description,
            'messageLink' => $link
        ]);
    }
}
