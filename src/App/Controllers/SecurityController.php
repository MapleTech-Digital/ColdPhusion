<?php

namespace App\Controllers;

use Core\BaseController;
use App\Repositories;

require_once __DIR__ . '/../../Core/BaseController.php';
require_once __DIR__ . '/../Repositories/PostRepository.php';

class SecurityController extends BaseController
{
    public function login()
    {
        return $this->render('security.login');
    }
}
