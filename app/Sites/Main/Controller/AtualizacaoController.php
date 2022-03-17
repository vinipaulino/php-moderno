<?php

namespace App\Sites\Main\Controller;

use App\Core\Controller;
use App\Sites\Main\Model\AtualizacaoModel;

class AtualizacaoController extends Controller
{
    public function index()
    {
        $atualizacoes = (new AtualizacaoModel())->getAll();

        for ($i = 0; $i < count($atualizacoes); $i++) {
            $atualizacoes[$i]['descricao'] = html_entity_decode($atualizacoes[$i]['descricao']);

            $atualizacoes[$i]['descricao'] = strip_tags($atualizacoes[$i]['descricao']);

            $atualizacoes[$i]['descricao'] = mb_substr($atualizacoes[$i]['descricao'], 0, 100) . '...';
        }

        $this->view('atualizacao.index', [
            'atualizacoes' => $atualizacoes
        ]);
    }

    public function ver($id = -1)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $id = substr($id, 0, 5);

        if (!$id || $id == null || $id <= 0) {
            return $this->showMessage(
                'Não encontrado',
                'A atualização que você procura não pode ser encontrada',
                BASE . 'atualizacao/',
                404
            );
        }

        $atualizacao = (new AtualizacaoModel())->getById($id);

        if (!isset($atualizacao['id']) || $atualizacao['id'] == null || $atualizacao['id'] <= 0) {
            return $this->showMessage(
                'Não encontrado',
                'A atualização que você procura não pode ser encontrada',
                BASE . 'atualizacao/',
                404
            );
        }

        $atualizacao['descricao'] = html_entity_decode($atualizacao['descricao']);

        return $this->view('atualizacao.ver', [
            'atualizacao' => $atualizacao
        ]);
    }
}
