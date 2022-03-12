<?php

namespace App\Sites\Admin\Controller;

use App\Classes\Date;
use App\Classes\Input;
use App\Core\Controller;
use App\Sites\Admin\Entities\Usuario;

class UsuarioController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function index()
    {
        $this->view('usuario.index');
    }

    public function add()
    {
        $this->view('usuario.add');
    }

    public function insert()
    {
        $usuario = $this->getInput();

        $validateResponse = $this->validate($usuario, false);
        if ($validateResponse != null) {
            return $this->showMessage('Formulário inválido', $validateResponse, ADMIN_BASE . 'usuario/add');
        }

        dd($usuario);
    }

    /**
     * Recebe os dados do formulário e retorna em um novo objeto.
     *
     * @param  mixed $id ID do usuário, por padrão é nulo
     * @return Usuario Retorna o objeto com os dados devidamente filtrados
     */
    private function getInput(int $id = null): Usuario
    {
        return new Usuario(
            $id,
            Input::post('nome'),
            Input::post('usuario'),
            Input::post('senha'),
            Input::post('status', FILTER_SANITIZE_NUMBER_INT),
            Input::post('permissao', FILTER_SANITIZE_NUMBER_INT),
            Date::getCurrentDate()
        );
    }

    private function validate(Usuario $usuario, bool $validateId = true): ?string
    {
        if ($validateId) {
            if ($usuario->getId() <= 0 || $usuario->getId() == null) {
                return 'ID de usuário inválido.';
            }
        }

        if (strlen(trim($usuario->getNome())) <= 5) {
            return 'Nome inválido. Mínimo seis caracteres.';
        }

        if (strlen(trim($usuario->getUsuario())) <= 5) {
            return 'Usuário inválido. Mínimo seis caracteres.';
        }

        if (strlen(trim($usuario->getSenha())) <= 6) {
            return 'Senha inválida. Mínimo sete caracteres.';
        }

        if ($usuario->getStatus() < 1 || $usuario->getStatus() > 2) {
            return 'Status inválido. Selecione o status correto!';
        }

        if ($usuario->getPermissao() < 1 || $usuario->getPermissao() > 2) {
            return 'Permissão inválida. Selecione a permissão correta!';
        }

        return null;
    }
}
