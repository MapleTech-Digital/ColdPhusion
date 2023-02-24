<?php
session_start();

$config = Core\Config::Get();

$logger = Core\Logger\Logger::Get();

$logger->info('Bootstrapping application');
$router = Core\Router\Router::Get($config->config->getRaw('routes'));

$db = Core\Database\DBAL\Database::Get();

$logger->info('Completed bootstrapping');
