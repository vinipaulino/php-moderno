<?php

namespace App\Sites\Main\Controller;

use App\Classes\Input;
use App\Classes\Session;
use App\Core\Controller;
use App\Core\Security;
use App\Sites\Main\Model\LoginModel;

class LoginController extends Controller
{
    public function index()
    {
        $this->view('login.index', []);
    }

    public function logout()
    {
        $msg = Input::Get('msg');

        Session::destroy();

        return $this->showMessage(
            'Desconectado',
            $msg != '' ? $msg : 'Faça o login para entrar novamente.',
            BASE . 'login/'
        );
    }

    public function auth()
    {
        $usuario = strtolower(trim(Input::post('usuario')));
        $senha = Input::post('senha');

        $usuario = preg_replace('[^a-z0-9.]', '', $usuario);

        if (!$usuario || strlen($usuario) < 6 || $usuario == '') {
            return $this->showMessage(
                'Formulário inválido',
                'O usuário informado está mal formatado',
                BASE . 'login/',
                422
            );
        }

        if (!$senha || strlen($senha) < 7 || $senha == '') {
            return $this->showMessage(
                'Formulário inválido',
                'A senha informada está mal formatada',
                BASE . 'login/',
                422
            );
        }

        $usuarioDados = (new LoginModel())->getUserLoginData($usuario);

        if (!isset($usuarioDados['id']) || $usuarioDados['id'] == null || $usuarioDados['id'] <= 0) {
            return $this->showMessage(
                'Usuário inválido',
                'O usuário ou senha informado é inválido',
                BASE . 'login/',
                422
            );
        }

        if (!password_verify($senha, $usuarioDados['senha'])) {
            return $this->showMessage(
                'Usuário inválido',
                'O usuário ou senha informado é inválido',
                BASE . 'login/',
                422
            );
        }

        $params = [
            'logged'        => true,
            'id'            => $usuarioDados['id'],
            'permission'    => $usuarioDados['permissao']
        ];

        setcookie('username', $usuarioDados['nome'], 0, BASE);

        Security::login($params);

        redirect(ADMIN_BASE);
    }
}
