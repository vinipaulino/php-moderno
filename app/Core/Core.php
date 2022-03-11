<?php

namespace App\Core;

class Core
{
    private $explodeURI;

    private $area;

    /**
     * Método construtor que inicializa as funcões do core
     *
     * @return void
     */
    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'];

        $uri = substr($uri, 0, strpos($uri, '?'));

        $this->explodeURI = array_values(
            array_filter(explode('/', $uri))
        );

        for ($i = 0; $i < REMOVE_INDEX; $i++) {
            unset($this->explodeURI[$i]);
        }

        $this->explodeURI = array_values($this->explodeURI);

        $this->area = $this->getArea();

        $this->execute();
    }

    /**
     * Valida se existe parâmetros e retorna a área correta a ser carregada
     *
     * @return string Retorna a área do site, por padrão a Main
     */
    private function getArea(): string
    {
        if (!isset($this->explodeURI[0]) || !is_array($this->explodeURI) || count($this->explodeURI) === 0) {
            return 'Main';
        }

        foreach (AREAS as $area => $value) {
            if (strtolower($area) == $this->explodeURI[0]) {
                unset($this->explodeURI[0]);

                $this->explodeURI = array_values($this->explodeURI);

                return $value;
            }
        }

        return 'Main';
    }

    /**
     * Executa a controladora chamando o método e passando os parâmetros
     *
     * @return void
     */
    private function execute()
    {
        $controller = $this->getController();

        $method = $this->getMethod($controller);

        call_user_func_array(
            [
                new $controller(),
                $method
            ],
            $this->getParams()
        );
    }

    /**
     * Retorna a controller a ser utilizada com base na URI
     *
     * @return string Retorna a MainController caso a controller informada não exista
     */
    private function getController(): string
    {
        $contBase = "App\\Sites\\{$this->area}\\Controller\\";

        if (!isset($this->explodeURI[0])) {
            return $contBase . 'MainController';
        }

        $tmpController = $contBase . ucfirst($this->explodeURI[0]) . 'Controller';

        if (!class_exists($tmpController)) {
            return $contBase . 'MainController';
        }

        return $tmpController;
    }

    private function getMethod($controller): string
    {
        if (!isset($this->explodeURI[1])) {
            return 'index';
        }

        if (!method_exists($controller, $this->explodeURI[1])) {
            return 'index';
        }

        return $this->explodeURI[1];
    }

    private function getParams(): array
    {
        if (!isset($this->explodeURI[2])) {
            return [];
        }

        $tmpParams = [];

        for ($i = 2; $i < count($this->explodeURI); $i++) {
            $tmpParams[] = $this->explodeURI[$i];
        }

        return $tmpParams;
    }
}
