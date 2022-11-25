<?php

namespace App\Controllers;

use Core\BaseController;
use App\Repositories;

class HomeController extends BaseController
{
    public function index()
    {
        $posts = Repositories\PostRepository::getPosts();

        return $this->render('home', [
            'posts' => $posts
        ]);
    }

    public function about()
    {
        return $this->render('about');
    }
}
