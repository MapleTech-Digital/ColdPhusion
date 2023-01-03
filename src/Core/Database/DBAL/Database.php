<?php

namespace Core\Database\DBAL;

use Core\Config;
use Core\DevTools\VarDumper;

class Database
{
    private static ?Database $instance = null;

    private ?\PDO $connection = null;

    public function connect(): void
    {
        $config = Config::Get()->config->get('database');
        try {
            $pdo = new \PDO("mysql:host={$config->getString('host')};dbname={$config->getString('database')}", $config->getString('username'), $config->getString('password'));
            $pdo->query("select 1 = 1");
            $this->connection = $pdo;
        } catch (\PDOException $e) {
            echo "Database Connection Error: " . $e->getMessage();
            die();
        }
    }

    public function getConnection(): ?\PDO
    {
        return $this->connection;
    }

    public static function Get(): ?Database
    {
        if(self::$instance === null) {
            self::$instance = new self();
            self::$instance->connect();
        }

        return self::$instance;
    }
}
