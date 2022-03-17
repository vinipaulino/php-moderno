<?php

namespace App\Sites\Admin\Model;

use App\Core\Model;
use App\Sites\Admin\Entities\Atualizacao;
use App\Sites\Admin\Entities\Usuario;

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

    public function insert(Atualizacao $atualizacao): int
    {
        $sql = '
            INSERT INTO `atualizacao` (
                titulo,
                descricao,
                usuario_id
            )
            VALUES (
                :titulo,
                :descricao,
                :usuarioid
            );';

        $params = [
            ':titulo'       => $atualizacao->getTitulo(),
            ':descricao'    => $atualizacao->getDescricao(),
            ':usuarioid'    => $atualizacao->getUsuario()->getId()
        ];

        if (!$this->pdo->executeNonQuery($sql, $params)) {
            return -1;
        }

        return $this->pdo->getLastID();
    }

    public function update(Atualizacao $atualizacao): bool
    {
        $sql = '
            UPDATE `atualizacao` SET
                titulo = :titulo,
                descricao = :descricao
            WHERE id = :id;';

        $params = [
            ':titulo'       => $atualizacao->getTitulo(),
            ':descricao'    => $atualizacao->getDescricao(),
            ':id'           => $atualizacao->getId()
        ];

        return $this->pdo->executeNonQuery($sql, $params);
    }

    public function getById(int $id): Atualizacao
    {
        $sql = '
            SELECT
                id,
                titulo,
                descricao
            FROM `atualizacao`
            WHERE id = :id;';

        $dr = $this->pdo->executeQueryOneRow($sql, [
            ':id' => $id
        ]);

        return $this->collection($dr);
    }

    public function getAll()
    {
        $sql = '
            SELECT
                a.id,
                a.titulo,
                u.nome as usuario_nome
            FROM atualizacao a
            INNER JOIN usuario u ON u.id = a.usuario_id
            ORDER BY a.id DESC;';

        $dt = $this->pdo->executeQuery($sql);

        $list = [];

        foreach ($dt as $dr) {
            $list[] = $this->collection($dr);
        }

        return $list;
    }

    private function collection(array $param): Atualizacao
    {
        $atualizacao = new Atualizacao();

        $atualizacao->setId($param['id'] ?? null)
            ->setTitulo($param['titulo'] ?? null)
            ->setDescricao($param['descricao'] ?? null)
            ->setUsuario(new Usuario(
                $param['usuario_id'] ?? null,
                $param['usuario_nome'] ?? null
            ));

        return $atualizacao;
    }
}
