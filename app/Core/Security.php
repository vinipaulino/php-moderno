<?php

namespace App\Core;

use App\Classes\Session;

class Security
{
    public static function protected(array $permissionLevel = [])
    {
        if (Session::get('id') == null || Session::get('id') <= 0) {
            redirect(BASE . 'login/');
            die();
        }

        if (Session::get('permission') == null || Session::get('permission') <= 0) {
            redirect(BASE . 'login/');
            die();
        }

        if (!in_array(Session::get('permission'), $permissionLevel)) {
            redirect(BASE . 'login/logout/?msg=Você tentou acessar uma área restrita');
            die();
        }
    }

    public static function login(array $params): bool
    {
        try {
            foreach ($params as $key => $value) {
                Session::add($key, $value);
            }

            return true;
        } catch (\Throwable | \Exception $ex) {
            dd($ex->getMessage());
            return false;
        }
    }
}
