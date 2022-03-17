<?php

namespace App\Sites\Main\Model;

use App\Core\Model;

/**
 * Gerencia o acesso a tabela atualizacao
 */
class AtualizacaoModel
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

    public function getById(int $id): array
    {
        $sql = '
            SELECT
                a.id,
                a.titulo,
                a.descricao,
                u.nome as usuario_nome
            FROM `atualizacao` a
            INNER JOIN usuario u ON u.id = a.usuario_id
            WHERE a.id = :id;';

        $dr = $this->pdo->executeQueryOneRow($sql, [
            ':id' => $id
        ]);

        return $this->collection($dr);
    }

    public function getAll(int $limit = 10): array
    {
        $sql = '
            SELECT
                id,
                titulo,
                descricao
            FROM `atualizacao`
            ORDER BY id DESC
            LIMIT :limit';

        $dt = $this->pdo->executeQuery($sql, [
            ':limit'    => $limit
        ]);

        $list = [];

        foreach ($dt as $dr) {
            $list[] = $this->collection($dr);
        }

        return $list;
    }

    private function collection(array $param): array
    {
        return [
            'id'            => $param['id'] ?? null,
            'titulo'        => $param['titulo'] ?? null,
            'descricao'     => $param['descricao'] ?? null,
            'usuario_nome'  => $param['usuario_nome'] ?? null,
        ];
    }
}
