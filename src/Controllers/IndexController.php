<?php

namespace Main\Controllers;

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
//use \Main\PDO;
use \Main\Mock\PDO;


    /**
    *   NOTE that the following are injected into your controller 
    *   Renderer $renderer - Template Engine
    *   PDO $conn - PDO
    *   Dependency Injecting makes testing easier!
    ***/

    class IndexController implements IController {

        private $data;
        use \Main\Traits\DemoData;
        use \Main\Traits\MenuData;

        public function __construct(
            Renderer $renderer,
            PDO $conn
        ) {

            $this->renderer = $renderer;
            $this->conn = $conn;

            $this->data = [
                    'appName' => self::appName() //from DemoData.php
                ];
        }



        public function get(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('index');
            $html = $this->renderer->render('index', $this->data);
            $response->body($html);
            return $response;

        }

    }