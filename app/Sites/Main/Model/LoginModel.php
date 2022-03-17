<?php

namespace App\Sites\Main\Model;

use App\Core\Model;

class LoginModel
{
    private Model $pdo;

    public function __construct()
    {
        $this->pdo = new Model();
    }

    /**
     * Obtém os dados do usuário para utilização no módulo de autenticação
     *
     * @param   string  $username  Nome de usuário
     * @return  array              Retorna um array com as seguintes informações ['id', 'nome', 'permissao']
     */
    public function getUserLoginData(string $username): array
    {
        $sql = "
            SELECT
                id,
                nome,
                permissao,
                senha
            FROM `usuario`
            WHERE LOWER(usuario) = :usuario
            AND status = :status";

        $params = [
            ':usuario' => $username,
            ':status' => 1 // Ativo
        ];

        $dr = $this->pdo->executeQueryOneRow($sql, $params);

        return [
            'id'        => $dr['id'] ?? null,
            'nome'      => $dr['nome'] ?? null,
            'permissao' => $dr['permissao'] ?? null,
            'senha'     => $dr['senha'] ?? null
        ];
    }
}
