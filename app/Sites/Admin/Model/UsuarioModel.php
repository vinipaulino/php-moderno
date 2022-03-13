<?php

namespace App\Sites\Admin\Model;

use App\Core\Model;
use App\Sites\Admin\Entities\Usuario;

/**
 * Gerencia o acesso a tabela usuário
 */
class UsuarioModel
{
    private Model $pdo;

    /**
     * Inicializa a classe model na propriedade $pdo
     *
     * @return void
     */
    public function __construct()
    {
        $this->pdo = new Model();
    }

    /**
     * Insere um novo usuário na tabela
     *
     * @param  Usuario $usuario Objeto usuário com todos os campos
     * @return int Retorna -1 em caso de erro ou um valor positivo com o ID do usuário cadastrado
     */
    public function insert(Usuario $usuario): int
    {
        $sql = '
            INSERT INTO `usuario` (
                `nome`,
                `usuario`,
                `senha`,
                `status`,
                `permissao`,
                `data`
            ) VALUES (
                :nome,
                :usuario,
                :senha,
                :status,
                :permissao,
                :data
            );';

        $params = [
            ':nome' => $usuario->getNome(),
            ':usuario' => $usuario->getUsuario(),
            ':senha' => $usuario->getSenha(),
            ':status' => $usuario->getStatus(),
            ':permissao' => $usuario->getPermissao(),
            ':data' => $usuario->getData()
        ];

        if (!$this->pdo->executeNonQuery($sql, $params)) {
            return -1;
        }

        return $this->pdo->getLastID();
    }

    public function update(Usuario $usuario): bool
    {
        $sql = '
            UPDATE `usuario` SET
                `nome` = :nome,
                `usuario` = :usuario,
                `status` = :status,
                `permissao` = :permissao
            WHERE id = :id;';

        $params = [
            ':nome'         => $usuario->getNome(),
            ':usuario'      => $usuario->getUsuario(),
            ':status'       => $usuario->getStatus(),
            ':permissao'    => $usuario->getPermissao(),
            ':id'           => $usuario->getId()
        ];

        return $this->pdo->executeNonQuery($sql, $params);
    }

    /**
     * Altera a senha do usuário infomado
     *
     * @param   string  $newPass  Nova senha já criptografada
     * @param   int     $userId   ID do usuário na qual a senha será alterada
     * @return  bool              Retorna TRUE se a senha foi alterada e FALSE do contrário
     */
    public function updatePassword(string $newPass, int $userId): bool
    {
        $sql = '
            UPDATE `usuario` SET
                senha = :senha
            WHERE id = :id';

        $params = [
            ':id'       => $userId,
            ':senha'    => $newPass
        ];

        return $this->pdo->executeNonQuery($sql, $params);
    }

    /**
     * Checa se o usuário informado já está cadastrado
     *
     * @param   string  $usuarioNome  Nome de usuário
     * @return  bool                  Retorna TRUE se existe e FALSE do contrário
     */
    public function checkUserAlreadyExists(string $usuarioNome, int $usuarioId = -1): bool
    {
        $sql = 'SELECT id FROM `usuario` where `usuario` = :usuario AND id <> :id';

        $dr = $this->pdo->executeQueryOneRow($sql, [
            ':usuario'  => $usuarioNome,
            ':id'       => $usuarioId
        ]);

        if (isset($dr['id']) && $dr['id'] != null) {
            return true;
        }

        return false;
    }

    /**
     * Retorna todos os usuários cadastrados
     *
     * @return  array
     */
    public function getAll(): array
    {
        $sql = '
            SELECT
                `id`,
                `nome`,
                `usuario`,
                `status`,
                `permissao`,
                `data`
            FROM `usuario`
            ORDER BY `nome` ASC';

        $dt = $this->pdo->executeQuery($sql);

        $listaUsuario = [];

        foreach ($dt as $dr) {
            $listaUsuario[] = $this->collection($dr);
        }

        return $listaUsuario;
    }

    /**
     * Recebe um ID e retorna as informações do seu usuário
     *
     * @param   int      $id  ID do usuário
     *
     * @return  Usuario       Objeto com as informações do usuário
     */
    public function getById(int $id): ?Usuario
    {
        $sql = '
        SELECT
            `id`,
            `nome`,
            `usuario`,
            `status`,
            `permissao`,
            `data`
        FROM `usuario`
        WHERE id = :id';

        $dr = $this->pdo->executeQueryOneRow($sql, [
            ':id' => $id
        ]);

        if (!$dr || $dr == null) {
            return null;
        }

        return $this->collection($dr);
    }

    /**
     * Recebe o array de retorno do banco de dados e retorna em objeto Usuario devidamente estruturado
     *
     * @param   array    $param  Array de retorno do banco
     *
     * @return  Usuario          Objeto devidamente estruturado
     */
    private function collection(array $param): Usuario
    {
        return new Usuario(
            $param['id'] ?? null,
            $param['nome'] ?? null,
            $param['usuario'] ?? null,
            null,
            $param['status'] ?? null,
            $param['permissao'] ?? null,
            $param['data'] ?? null,
        );
    }
}
