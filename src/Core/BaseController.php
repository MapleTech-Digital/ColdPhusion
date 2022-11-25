<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;
use League\Plates\Engine as TemplateEngine;
use League\Plates\Extension\Asset;
use League\Plates\Extension\URI;

class BaseController
{
    // Create new Plates instance
    public $templateEngine = null;

    public function __construct()
    {
        $this->templateEngine = new TemplateEngine(__DIR__ . "/../App/Views/");
        $this->templateEngine->loadExtension(new Asset(__DIR__ . "/../Core/../../public/"));
        $this->templateEngine->loadExtension(new URI(Request::Get()->getServer()->getString('PATH_INFO')));
    }

    public function render($view, $data = [], $code = 200, $headers = []): Response {
        $response = new Response();
        $response->code = $code;
        $response->headers = $headers;
//        $response->body = '';

//        $view = __DIR__ . "/../App/Views/{$view}.php";
//        if(file_exists($view)) {
//            ob_start();
//            extract($data, EXTR_OVERWRITE);
//            require $view;
//            $response->body = ob_get_clean();
//        }

        $response->body = $this->templateEngine->render($view, $data);

        return $response;
    }
}
