<?php

namespace Core\Environment;

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
            [$key, $value] = explode('=', trim($datum));
            $key = trim($key);
            $value = trim($value);
        }

    }

    public static function Initialize(string $path): void
    {
        new DotEnv($path);
    }
}
