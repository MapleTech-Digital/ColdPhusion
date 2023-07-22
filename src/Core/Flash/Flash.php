<?php

namespace Core\Flash;

use Core\Config;
use Core\DevTools\VarDumper;
use Core\Parameters;

class Flash
{
    public static function Set(string $key, mixed $value) {
        $_SESSION[$key] = $value;
        return $value;
    }

    public static function Exists(string $key) {
        return isset($_SESSION[$key]) && $_SESSION[$key];
    }

    public static function Get(string $key) {
        if(isset($_SESSION[$key]) && $_SESSION[$key]) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }

        return null;
    }
}
