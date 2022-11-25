<?php

namespace App\Controllers;

use Core\BaseController;
use App\Repositories;

class PostController extends BaseController
{

    public function index($id)
    {
        $post = Repositories\PostRepository::getPost($id);
        return $this->render('post', [
            'post' => $post
        ]);
    }

}
