<?php

// return whatever asset so long as we're not on this front controller
//echo "<pre>";
//var_dump($_SERVER);
//die();
//if($_SERVER['SCRIPT_NAME'] !== '/index.php') {
//    return false;
//}

// Include Composer Files
require_once __DIR__ . '/../vendor/autoload.php';

// Include System Files
require_once __DIR__ . '/../src/bootstrap.php';

$matched_route = Core\Router\Router::Get()->match(Core\Http\Request::Get()->getServer()->getString('REQUEST_URI'));
if(!$matched_route) {
    echo "404 not found";
    die();
}

$controller = new $matched_route['controller']();
$response = call_user_func_array([$controller, $matched_route['action']], $matched_route['parameters']);

// basic hack for now
if($response instanceof \Core\Http\RedirectResponse) {
    header("location: {$response->target}");
}

ob_start();

echo $response->body;

ob_end_flush();
die();
