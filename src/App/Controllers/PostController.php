<?php

namespace App\Controllers;

use Core\BaseController;
use App\Repositories;
use Core\DevTools\VarDumper;

class PostController extends BaseController
{

    public function index($id)
    {
        $post = Repositories\PostRepository::getPost($id);
        return $this->render('post', [
            'post' => $post
        ]);
    }

    public function random()
    {
        $post = Repositories\PostRepository::getRandomPost();
        return $this->render('about');
    }

}
