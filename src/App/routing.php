<?php

$routes = [
    'index' => [
        'route' => '',
        'controller' => 'App\Controllers\HomeController::index'
    ],
    'post_view' => [
        'route' => '/post/:id',
        'controller' => 'App\Controllers\PostController::index'
    ],
    'about' => [
        'route' => '/about',
        'controller' => 'App\Controllers\HomeController::about'
    ],
    'security_login' => [
        'route' => '/security/login',
        'controller' => 'App\Controllers\SecurityController::login'
    ]
];

return $routes;
