<?php

namespace Main\Controllers;

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
//use \Main\PDO;
use \Main\Mock\PDO;


    /**
    *   NOTE that the following are injected into your controller 
    *   Request $request
    *   Response $response 
    *   Renderer $renderer - Template Engine
    *   PDO $conn - PDO
    *   Dependency Injecting makes testing easier!
    ***/

    class DemoController implements IController {

        private $data;
        use \Main\Traits\DemoData;
        use \Main\Traits\MenuData;

        public function __construct(
            Renderer $renderer,
            PDO $conn
        ) {

            $this->renderer = $renderer;
            $this->conn = $conn;

            //Database Layer example
            $mock_database_users = $conn->getUsers();

            //Using PHP Trait example
            $trait_lint = self::getLintHtmlFromTrait();


            $howToRemove = '<div class="alert alert-success" role="alert">'. 
                'To disable the bootstrap lint test, remove {{{bootstrap_lint}}} from the <i>src/templates/Home-demo.html</i> template file</b>.'.
                '</div>';

            $this->data = [
                    'appName' => self::appName(), //from DemoData.php
                    'month' =>          date('M'),
                    'day' =>            date('d'),
                    'year' =>           date('Y'),
                    'today'=>           date('l'),
                    'time'=>            date( "F, d"),
                    'bootstrapLint'=>  $trait_lint,
                    'users' =>          $mock_database_users,
                    'howToRemove' => $howToRemove,
                    'appTree' => self::appTree()
                ];
        }

        public function installationCheck() {
      

            $this->data['check']['installed'] = [];
            $path = 'public/assets/bootstrap/';
            $dirs = ['dist','docs','fonts'];
            foreach ($dirs as $dir) {
                $this->data['check']['installed'][$dir] = is_dir($path . $dir) ? true : false;
            }

            $this->data['installation_ok'] = true;

            foreach ($this->data['check']['installed'] as $key => $value) {

                if ($value == false) {
                    $this->data['installation_ok'] = $value;
                } 
            }

            $okHtml = '<div class="alert alert-success" role="alert">'. 
                '<strong>Well done!</strong> You\'ve successfully installed <b>php-seed-bootstrap</b></div>';
            $failedHtml = '<div style="float:right; border: 2px solid;
        border-radius: 3px; padding: 2px; background-color:yellow;"><h2>Installation incomplete!</h2>'.
                '<font color="red">Bootstrap assets not copied to public/assets/<br>Reinstall by running <b>composer reinstall</b></font></div>';

            $this->data['installation_status'] =  $this->data['installation_ok'] ? $okHtml : $failedHtml;

        }


        public function get(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('Home-demo');

            self::installationCheck();
            $html = $this->renderer->render('Home-demo', $this->data);

            $response->body($html);
            return $response;

        }

        
        public function dashboardDemo(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('dashboard');

            $html = $this->renderer->render('Demo-dashboard', $this->data);

            $response->body($html);
            return $response;
        }

        public function jumbotronDemo(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('jumbotron');

            $html = $this->renderer->render('Demo-jumbotron', $this->data);

            $response->body($html);
            return $response;
        }


        public function coverDemo(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('cover');

            $html = $this->renderer->render('Demo-cover', $this->data);

            $response->body($html);
            return $response;
        }

        public function simpleSidebarDemo(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('simplesidebar');

            $html = $this->renderer->render('Demo-simplesidebar', $this->data);

            $response->body($html);
            return $response;
        }

        public function vanillaDemo(Request $request, Response $response) {
            $this->data['demo_menu']  = self::getDemoMenu('vanilla');

            $html = $this->renderer->render('Demo-vanilla', $this->data);

            $response->body($html);
            return $response;
        }

    }