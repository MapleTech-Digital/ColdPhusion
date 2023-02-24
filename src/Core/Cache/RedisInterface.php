<?php

namespace Core\Cache;

use Core\Config;
use Core\DevTools\VarDumper;
use Core\Logger\Logger;

class RedisInterface
{
    private static ?RedisInterface $instance = null;

    private ?\Redis $connection = null;

    /**
     * @throws \RedisException
     */
    public function connect(): void
    {
        $config = Config::Get()->config->get('redis');

        Logger::Debug("Establishing Redis Connection", ["config" => $config->getAll()]);

        $redis = new \Redis();
        try {
            $redis->connect($config->getString('host'), $config->getInt('port'), $config->getFloat('timeout'));
            $redis->setOption(\Redis::OPT_SERIALIZER, \Redis::SERIALIZER_JSON);
            $redis->setOption(\Redis::OPT_PREFIX, $config->getString('prefix') ?: null);
            $redis->select($config->getInt('database'));
        } catch (\RedisException $redisException) {
            Logger::Error("RedisException connection failed.", ['message' => $redisException->getMessage()]);
            throw $redisException;
        }

        $this->connection = $redis;
    }

    public function getConnection(): ?\Redis
    {
        return $this->connection;
    }

    public static function Get(): ?RedisInterface
    {
        if(self::$instance === null) {
            Logger::Debug("Creating new Redis Interface Instance");
            self::$instance = new self();
            self::$instance->connect();
        }

        return self::$instance;
    }
}
