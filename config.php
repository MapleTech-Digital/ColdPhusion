<?php

$config = [
    // Database Module
    "database" => [
            "host" => "localhost",
            "port" => 3306,
        "database" => "phlog",
        "username" => "root",
        "password" => "toor"
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
