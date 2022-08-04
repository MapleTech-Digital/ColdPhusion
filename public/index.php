<?php

// return whatever asset so long as we're not on this front controller
if($_SERVER['SCRIPT_NAME'] !== '/index.php') {
    return false;
}

require_once __DIR__ . '/../src/bootstrap.php';

$matched_route = Core\Router\Router::Get()->match(Core\Http\Request::Get()->getServer()->getString('PATH_INFO'));
if(!$matched_route) {
    echo "404 not found";
    die();
}

// @todo Can automate this with a glob to the controller directory, so the user doesn't need to sit here and do this for ever new controller authored
require_once __DIR__ . '/../src/App/Controllers/HomeController.php';
require_once __DIR__ . '/../src/App/Controllers/PostController.php';
require_once __DIR__ . '/../src/App/Controllers/SecurityController.php';

$controller = new $matched_route['controller']();
$response = call_user_func_array([$controller, $matched_route['action']], $matched_route['parameters']);

ob_start();

echo $response->body;

ob_end_flush();
die();
