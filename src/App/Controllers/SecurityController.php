<?php

namespace App\Controllers;

use Core\BaseController;
use App\Repositories;
use Core\DevTools\VarDumper;
use Core\Http\Request;

class SecurityController extends BaseController
{
    public function login()
    {
        $request = Request::Get();

        if(!$request->getPost()->isEmpty()) {
            VarDumper::Dump($request->getPost()->getEmail('email'), true);
        }

        return $this->render('security.login');
    }
}
