<?php
include('ControllerInterface.php');

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
use \Main\Mock\PDO;


    /**
    *   NOTE that the following are injected into your controller
    *   Renderer $renderer - Template Engine
    *   PDO $conn - PDO
    *   Dependency Injecting makes testing easier!
    ***/

    class IndexController implements ControllerInterface {

        private $data;
        //use DemoData;

        public function __construct(
            Renderer $renderer,
            PDO $conn
        ) {

            $this->renderer = $renderer;
            $this->conn = $conn;

            $this->data = [
                    'appName' => "PHP-MVC Template"
                ];
        }

        public function get(Request $request, Response $response) {
            $this->data['getVar'] = $request->__get('get');
            $html = $this->renderer->render('index', $this->data);
            $response->body($html);
            return $response;

        }

        public function createFoo(Request $request, Response $response) {
            return self::get($request, $response);
        }
        public function readFoo(Request $request, Response $response) {
            return self::get($request, $response);
        }
        public function updateFoo(Request $request, Response $response) {
            return self::get($request, $response);
        }
        public function deleteFoo(Request $request, Response $response) {
            return self::get($request, $response);
        }


    }
