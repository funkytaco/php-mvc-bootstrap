<?php

namespace Main\Controllers;

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;

class PostController
{
    private $post_repository;

    public function __construct(\Klein\Request $request, \Klein\Response $response)
    {
        $this->id = $request->id;
    }

    public function create(\Klein\Request $request, \Klein\Response $response)
    {
        $response->body('create post with ID: '. $this->id);
        return $response;
    }

    public function read(\Klein\Request $request, \Klein\Response $response)
    {
        $response->body('read post with ID: '. $this->id);
        return $response;
    }

    public function update(\Klein\Request $request, \Klein\Response $response)
    {
        $response->body('update post with ID: '. $this->id);
        return $response;
    }

    public function delete(\Klein\Request $request, \Klein\Response $response)
    {
        $response->body('delete post with ID: '. $this->id);
        return $response;
    }


}