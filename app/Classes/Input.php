<?php

namespace App\Classes;

class Input
{
    /**
     * Retorna informações do tipo GET tratado pelo parâmetro informado.
     *
     * @param  string $field Nome do campo
     * @param  int $filter Tipo de filtro, por padrão filta string
     * @return mixed Retorna a informação do campo ou false caso não encontre.
     */
    public static function get(string $field, int $filter = FILTER_SANITIZE_STRING): mixed
    {
        return filter_input(INPUT_GET, $field, $filter);
    }

    /**
     * Retorna informações do tipo POST tratado pelo parâmetro informado.
     *
     * @param  string $field Nome do campo
     * @param  int $filter Tipo de filtro, por padrão filta string
     * @return mixed Retorna a informação do campo ou false caso não encontre.
     */
    public static function post(string $field, int $filter = FILTER_SANITIZE_STRING): mixed
    {
        return filter_input(INPUT_POST, $field, $filter);
    }
}
