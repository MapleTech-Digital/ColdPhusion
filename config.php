<?php

\Core\Environment\DotEnv::Initialize(__DIR__ . '/.env');

$config = [
    // Database Module
    "database" => [
            "host" => getenv("DB_HOST") ?: "localhost",
            "port" => getenv("DB_PORT") ?: 3306,
        "database" => getenv("DB_NAME") ?: "phlog",
        "username" => getenv("DB_USER") ?: "root",
        "password" => getenv("DB_PASS") ?: "toor"
    ],

    // Route Module
    'routes' => require __DIR__ . '/src/App/routing.php',

    // Logging Module
    'logging' => [
        'name' => 'app',
        'outdir' => __DIR__ . '/var/logs',
        'minlevel' => 'info',
    ]
];

return $config;
