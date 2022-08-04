<?php

namespace Core\Logger;

use Core\Parameters;

class Logger
{
    private static $instance = null;

    public const LEVEL_DEBUG = 0;
    public const LEVEL_INFO = 1;
    public const LEVEL_WARN = 2;
    public const LEVEL_ERROR = 3;
    public const LEVEL_FATAL = 4;
    public static $Levels = [
        'debug' => self::LEVEL_DEBUG,
        'info' => self::LEVEL_INFO,
        'warn' => self::LEVEL_WARN,
        'error' => self::LEVEL_ERROR,
        'fatal' => self::LEVEL_FATAL
    ];
    public static $LevelStrings = [
        self::LEVEL_DEBUG => 'DEBUG',
        self::LEVEL_INFO => 'INFO',
        self::LEVEL_WARN => 'WARNING',
        self::LEVEL_ERROR => 'ERROR',
        self::LEVEL_FATAL => 'FATAL'
    ];

    private $request_id = null;
    private $logname = 'log';
    private $logpath = __DIR__ . "/../../log.log";
    private $minlevel = self::LEVEL_DEBUG;

    public function __construct(?Parameters $config = null)
    {
        if($config) {
            $this->load($config);
        }
    }

    private function load(Parameters $config): void
    {
        if($newname = $config->getString('name')) {
            $this->logname = $newname;
        }
        if($newpath = $config->getString('outdir')) {
            $this->logpath = "{$newpath}/{$this->logname}.log";
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
        $output[] = json_encode($context);

        @file_put_contents($this->logpath, implode(' ', $output) . PHP_EOL, FILE_APPEND);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_DEBUG, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_INFO, $message, $context);
    }

    public function warn(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_WARN, $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_ERROR, $message, $context);
    }

    public function fatal(string $message, array $context = []): void
    {
        $this->log(self::LEVEL_FATAL, $message, $context);
    }

    public static function Get(?Parameters $config = null): self
    {
        if(self::$instance === null) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }
}
