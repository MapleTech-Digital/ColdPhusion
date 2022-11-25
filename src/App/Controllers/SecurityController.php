<?php

namespace App\Controllers;

use Core\BaseController;
use App\Repositories;

class SecurityController extends BaseController
{
    public function login()
    {
        return $this->render('security.login');
    }
}
