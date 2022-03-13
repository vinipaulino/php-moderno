<?php

namespace App\Sites\Admin\Controller;

use App\Classes\Date;
use App\Classes\Input;
use App\Core\Controller;
use App\Sites\Admin\Entities\Usuario;
use App\Sites\Admin\Model\UsuarioModel;

class UsuarioController extends Controller
{
    public function __construct()
    {
        parent::__construct('Admin');
    }

    public function index()
    {
        $usuarios = (new UsuarioModel())->getAll();

        $this->view('usuario.index', [
            'usuarios' => $usuarios
        ]);
    }

    public function add()
    {
        $this->view('usuario.add');
    }

    public function pass($id = -1)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if (!$id || $id <= 0 || $id === null) {
            return $this->showMessage(
                'ID inválido',
                'O ID do usuário informado esta no formato incorreto.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $usuario = (new UsuarioModel())->getById($id);

        if ($usuario == null || $usuario->getId() == null || $usuario->getId() === 0) {
            return $this->showMessage(
                'Usuário não encontrado',
                'O usuário selecionado não existe ou foi removido.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $this->view('usuario.pass', [
            'usuario' => $usuario
        ]);
    }

    public function updatePass($id = -1)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if (!$id || $id <= 0 || $id === null) {
            return $this->showMessage(
                'ID inválido',
                'O ID do usuário informado esta no formato incorreto.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $usuarioModel = new UsuarioModel();

        $usuario = $usuarioModel->getById($id);

        if ($usuario == null || $usuario->getId() == null || $usuario->getId() === 0) {
            return $this->showMessage(
                'Usuário não encontrado',
                'O usuário selecionado não existe ou foi removido.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $newPass = Input::post('senha');

        if (strlen($newPass) < 7) {
            return $this->showMessage(
                'Formulário inválido',
                'A senha deve conter ao menos sete caracteres.',
                ADMIN_BASE . 'usuario/pass/' . $id
            );
        }

        $newPass = Usuario::hashPassword($newPass);

        if (!$usuarioModel->updatePassword($newPass, $id)) {
            return $this->showMessage(
                'Erro',
                'Houve um erro ao tentar alterar a senha, por favor tente mais tarde.',
                ADMIN_BASE . 'usuario/pass/' . $id
            );
        }

        return $this->showMessage(
            'Senha alterada',
            'Senha alterada com sucesso.',
            ADMIN_BASE . 'usuario/'
        );
    }

    public function edit($id = -1)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if (!$id || $id <= 0 || $id === null) {
            return $this->showMessage(
                'ID inválido',
                'O ID do usuário informado esta no formato incorreto.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $usuario = (new UsuarioModel())->getById($id);

        if ($usuario == null || $usuario->getId() == null || $usuario->getId() === 0) {
            return $this->showMessage(
                'Usuário não encontrado',
                'O usuário selecionado não existe ou foi removido.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $this->view('usuario.edit', [
            'usuario' => $usuario
        ]);
    }

    public function insert()
    {
        $usuario = $this->getInput();

        $validateResponse = $this->validate($usuario, false);
        if ($validateResponse != null) {
            return $this->showMessage('Formulário inválido', $validateResponse, ADMIN_BASE . 'usuario/add');
        }

        $usuarioModel = new UsuarioModel();

        if ($usuarioModel->checkUserAlreadyExists($usuario->getUsuario())) {
            return $this->showMessage(
                'Formulário inválido',
                'O usuário que você está tentando inserir já existe',
                ADMIN_BASE . 'usuario/add'
            );
        }

        $usuario->setSenha(
            Usuario::hashPassword($usuario->getSenha())
        );

        $usuarioId = $usuarioModel->insert($usuario);

        if ($usuarioId === -1) {
            return $this->showMessage(
                'Erro',
                "Houve um erro ao tentar cadastrar o usuário, por favor, tente novamente mais tarde.",
                ADMIN_BASE . 'usuario/add'
            );
        }

        redirect(ADMIN_BASE . 'usuario/edit/' . $usuarioId);
    }

    public function update($usuarioId = -1)
    {
        $usuarioId = filter_var($usuarioId, FILTER_SANITIZE_NUMBER_INT);

        if (!$usuarioId || $usuarioId <= 0) {
            return $this->showMessage(
                'ID inválido',
                'O ID do usuário informado esta no formato incorreto.',
                ADMIN_BASE . 'usuario/'
            );
        }

        $usuario = $this->getInput($usuarioId);

        $validateResponse = $this->validate($usuario);
        if ($validateResponse != null) {
            return $this->showMessage(
                'Formulário inválido',
                $validateResponse,
                ADMIN_BASE . 'usuario/'
            );
        }

        $usuarioModel = new UsuarioModel();

        if ($usuarioModel->checkUserAlreadyExists($usuario->getUsuario(), $usuarioId)) {
            return $this->showMessage(
                'Formulário inválido',
                'O usuário que você está tentando alterar já existe',
                ADMIN_BASE . 'usuario/edit/' . $usuarioId
            );
        }

        if (!$usuarioModel->update($usuario)) {
            return $this->showMessage(
                'Erro',
                'Houve um erro ao tentar atualizar as informações.',
                ADMIN_BASE . 'usuario/edit/' . $usuarioId
            );
        }

        redirect(ADMIN_BASE . 'usuario/edit/' . $usuarioId);
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

    /**
     * Recebe o objeto usuário e valida se os campos estão corretos
     *
     * @param  Usuario $usuario Objeto usuário
     * @param  bool $validateId Se o valor for definido como TRUE, então o campo Id será validado.
     * @return string Retorna NULL se tudo estiver certo ou uma mensagem com o erro encontrado.
     */
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

        if (!$validateId && strlen(trim($usuario->getSenha())) <= 6) {
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
