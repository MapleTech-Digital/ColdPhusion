<?php

\Core\Environment\DotEnv::Initialize(__DIR__ . '/.env');

$config = [
    // Database Module
    "database" => [
        "host" => getenv("DB_HOST") ?: "host.docker.internal",
        "port" => getenv("DB_PORT") ?: 3306,
        "database" => getenv("DB_NAME") ?: "phlog",
        "username" => getenv("DB_USER") ?: "root",
        "password" => getenv("DB_PASS") ?: ""
    ],

    "redis" => [
        "host" => getenv("REDIS_HOST") ?: "redis_cache",
        "port" => getenv("REDIS_PORT") ?: 6379,
        "prefix" => getenv("REDIS_PREFIX") ?: "app:",
        "timeout" => getenv("REDIS_TIMEOUT") ?: 0,
        "database" => getenv("REDIS_DATABASE") ?: 0,
    ],

    "cache" => [
        "use_cache" => getenv("CACHE_USE") ?: false,
        "ttl" => getenv("CACHE_TTL") ?: 0
    ],

    // Route Module
    'routes' => require __DIR__ . '/src/App/routing.php',

    // Logger Module
    'logger' => [
        'name' => getenv("LOGGER_NAME") ?: "app",
        'outdir' => getenv("LOGGER_DIR") ?: __DIR__ . '/var/logs',
        'path' => getenv("LOGGER_PATH") ?: __DIR__ . '/var/logs',
        'minlevel' => getenv("LOGGER_MIN_LEVEL") ?: "info",
    ]
];

return $config;
