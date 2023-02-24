<?php

namespace Core\Logger;

use Core\Config;
use Core\DevTools\VarDumper;
use Core\Parameters;

class Logger
{
    private static ?Logger $instance = null;

    public const LEVEL_DEBUG = 0;
    public const LEVEL_INFO = 1;
    public const LEVEL_WARN = 2;
    public const LEVEL_ERROR = 3;
    public const LEVEL_FATAL = 4;
    public static array $Levels = [
        'debug' => self::LEVEL_DEBUG,
        'info' => self::LEVEL_INFO,
        'warn' => self::LEVEL_WARN,
        'error' => self::LEVEL_ERROR,
        'fatal' => self::LEVEL_FATAL
    ];
    public static array $LevelStrings = [
        self::LEVEL_DEBUG => 'DEBUG',
        self::LEVEL_INFO => 'INFO',
        self::LEVEL_WARN => 'WARNING',
        self::LEVEL_ERROR => 'ERROR',
        self::LEVEL_FATAL => 'FATAL'
    ];

    private $request_id = null;
    private string $logname = 'log';
    private string $logpath = "/../../log.log";
    private int $minlevel = self::LEVEL_DEBUG;

    public function __construct()
    {
        $this->request_id = str_replace('.', '', uniqid('rid-', true));
        $this->load();
    }

    private function load(): void
    {
        $config = Config::Get()->config->get('logger');

        if($newname = $config->getString('name')) {
            $this->logname = $newname;
        }
        if($newout = $config->getString('outdir')) {
            $this->logpath = "{$newout}/{$this->logname}.log";
        }
        if($newpath = $config->getString('path')) { // path variable overrides
            $this->logpath = $newpath;
        }
        if($newlevel = $config->getString('minlevel')) {
            $this->minlevel = self::$Levels[$newlevel];
        }
    }

    public function log(int $level, string $message, array $context = []): void
    {
        if($level < $this->minlevel) {
            return;
        }

        $output = [];

        $output[] = '['. self::$LevelStrings[$level] . ']';
        $output[] = '['. (new \DateTime())->format('Y-m-d h:i:s') .']';
        $output[] = '['. $this->request_id .']';
        $output[] = $message;
        $output[] = json_encode($context, JSON_THROW_ON_ERROR);

        @file_put_contents($this->logpath, implode(' ', $output) . PHP_EOL, FILE_APPEND);
    }

    public function debugLog(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    public function infoLog(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function warnLog(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_WARN, $message, $context);
    }

    public function errorLog(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function fatalLog(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_FATAL, $message, $context);
    }

    public static function Debug(string $message, array $context = []): void
    {
        self::Get()->debugLog($message, $context);
    }

    public static function Info(string $message, array $context = []): void
    {
        self::Get()->infoLog($message, $context);
    }

    public static function Warn(string $message, array $context = []): void
    {
        self::Get()->warnLog($message, $context);
    }

    public static function Error(string $message, array $context = []): void
    {
        self::Get()->errorLog($message, $context);
    }

    public static function Fatal(string $message, array $context = []): void
    {
        self::Get()->fatalLog($message, $context);
    }



    public static function Get(): self
    {
        if(self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
