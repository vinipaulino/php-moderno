<?php

namespace App\Classes;

class Session
{
    private static string $sessionName = 'PHP_ID';

    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_name(self::$sessionName);
            session_start();
        }
    }

    public static function destroy(): bool
    {
        self::start();

        return session_destroy();
    }

    public static function add(string $key, mixed $value)
    {
        self::start();

        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        self::start();

        if (!isset($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }
}
