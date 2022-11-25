<?php
session_start();

$config = Core\Config::Get();

$logger = Core\Logger\Logger::Get($config->config->get('logging'));

$logger->info('Bootstrapping application');
$router = Core\Router\Router::Get($config->config->getRaw('routes'));

$logger->debug('Establishing Connection');
$db = Core\Database\DBAL\Database::Get();

$logger->info('Completed bootstrapping');
