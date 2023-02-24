<?php

namespace Core;

use Core\DevTools\VarDumper;
use Core\Parameters;

class Config
{
    private static $instance = null;

    public $config = null;

    public function load()
    {
        $this->config = new Parameters(require(__DIR__ . '/../../config.php'));
    }

    public static function Get()
    {
        if(!self::$instance) {
            self::$instance = new self();
            self::$instance->load();
        }

        return self::$instance;
    }
}
