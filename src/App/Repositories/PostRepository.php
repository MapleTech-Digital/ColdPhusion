<?php

namespace App\Repositories;

use Core\Database\DBAL\Database;
use App\Models\Post;
use Core\Database\QueryBuilder\QueryBuilder;
use Core\DevTools\VarDumper;

class PostRepository
{
    public static function getPosts()
    {
        $db = Database::Get()->getConnection();

        $posts = [];

        $query = 'SELECT * FROM posts WHERE published = 1 AND NOW() > date_published ORDER BY date_published DESC';
        foreach($db->query($query) as $row) {
            $posts[] = new Post($row);
        }

        return $posts;
    }

    public static function getPostsQB()
    {
        $db = Database::Get()->getConnection();

        $query = QueryBuilder::Create()
                ->select()
                ->from('posts', 'p')
                ->where('p.published = 1')->andWhere('NOW() > p.date_published')
                ->orderBy('p.date_published', 'DESC');

        foreach($db->query($query) as $row) {
            $posts[] = new Post($row);
        }

        return $posts;
    }

    public static function getPost($id)
    {
        $db = Database::Get()->getConnection();

        $post = [];

        $query = 'SELECT * FROM posts WHERE id = :id AND published = 1 AND NOW() > date_published';
        $h = $db->prepare($query);
        $h->execute([':id' => $id]);
        $posts = $h->fetchAll();

        foreach($posts as $row) {
            $post = new Post($row);
        }

        return $post;
    }

    public static function getRandomPost()
    {
        $db = Database::Get()->getConnection();

        $post = [];

        $query = 'SELECT * FROM posts WHERE published = 1 AND NOW() > date_published ORDER BY RAND() LIMIT 1';
        foreach($db->query($query) as $row) {
            $post = new Post($row);
        }

        return $post;
    }
}
