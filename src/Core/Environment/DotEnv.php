<?php

namespace Core\Environment;

use Core\DevTools\VarDumper;
use Core\Logger\Logger;

class DotEnv
{
    public function __construct(string $path)
    {
        $this->init($path);

    }
    public function init(string $path)
    {
        $logger = Logger::Get();
        if(!file_exists($path)) {
            $logger->error(".env file doesn't exist at {$path}");
            return;
        }

        $data = explode(PHP_EOL, file_get_contents($path));
        foreach($data as $datum) {
            $datum = trim($datum);

            if(!$datum) {
                continue;
            }

            [$key, $value] = explode('=', $datum);
            $key = trim($key);
            $value = trim($value);

            putenv("{$key}={$value}");
        }

    }

    public static function Initialize(string $path): void
    {
        new DotEnv($path);
    }
}
