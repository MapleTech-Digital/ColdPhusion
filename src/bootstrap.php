<?php
session_start();

require_once __DIR__ . '/Core/Logger/Logger.php';
require_once __DIR__ . '/Core/Http/Request.php';
require_once __DIR__ . '/Core/Config.php';
require_once __DIR__ . '/Core/Database/DBAL/Database.php';
require_once __DIR__ . '/Core/Router/Router.php';


$config = Core\Config::Get();

$logger = Core\Logger\Logger::Get($config->config->get('logging'));

$logger->info('Bootstrapping application');
$router = Core\Router\Router::Get($config->config->getRaw('routes'));

$logger->debug('Establishing Connection');
$db = Core\Database\DBAL\Database::Get();

$logger->info('Completed bootstrapping');

