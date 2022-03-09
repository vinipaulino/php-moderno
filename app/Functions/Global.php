<?php

/**
 * Recebe uma informação e debuga o seu valor
 *
 * @param  mixed $param Valor a ser impresso
 * @return void
 */
function dd($param = null)
{
    echo '<pre>';
    print_r($param);
    echo '</pre>';

    die();
}

/**
 * Redireciona o usuário para página informada
 *
 * @param  mixed $url URL a ser redirecionada
 * @param  mixed $httpCode Código HTTP de resposta, por padrão é código 200
 * @return void
 */
function redirect(string $url, int $httpCode = 200)
{
    http_response_code($httpCode);

    header("Location: {$url}");
}

/**
 * Recebe uma infomação e imprime o seu valor em JSON
 *
 * @param  mixed $param
 * @param  mixed $httpCode Código HTTP de resposta, por padrão é código 200
 * @return void
 */
function responseJson($param, int $httpCode)
{
    header("Content-Type: application/json", true, $httpCode);

    echo json_decode($param);
}
