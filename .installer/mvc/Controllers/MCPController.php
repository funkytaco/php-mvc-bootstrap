<?php
require_once('ControllerInterface.php');

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
use \Main\Mock\PDO;
use Main\Modules\Date_Module;
use Main\Modules\MCP_Module;

    /**
    *   NOTE that the following are injected into your controller
    *   Renderer $renderer - Template Engine
    *   PDO $conn - PDO
    *   MCP_Module $mod_mcp
    *   Date_Module $mod_date
    *   Dependency Injecting makes testing easier!
    ***/

    class MCPController implements ControllerInterface {

        private $data;
        private Renderer $renderer;
        private PDO $conn;
        private MCP_Module $mod_mcp;
        private Date_Module $mod_date;

        public function __construct(
            Renderer $renderer,
            PDO $conn,
            MCP_Module $mod_mcp,
            Date_Module $mod_date
        ) {
            $this->renderer = $renderer;
            $this->conn = $conn;
            $this->mod_mcp = $mod_mcp;
            $this->mod_date = $mod_date;

            $this->data = [
<<<<<<< HEAD
                'appName' => "PHP-MVC Template",
                'myDateModule' => $this->mod_date->getDate(),
                'projectList' => self::getLegacyProjects()
            ];
=======
                    'appName' => "PHP-MVC Template",
                    'myDateModule' => $this->mod_date->getDate(),
                    'projectList' => self::getLegacyProjects()
                ];
>>>>>>> master
        }

        public function getLegacyProjects() {
            $projPaths = array();
            if (is_dir('Legacy')) {
                $paths = scandir('Legacy');
                foreach ($paths as $path) {
<<<<<<< HEAD
                    if (is_dir('Legacy/' . $path) && $path != '.' && $path != '..') {
=======
                    if (is_dir('Legacy' . $path) && $path != '.' && $path != '..') {
>>>>>>> master
                        $projPaths[] = $path;
                    }
                }
            }
            return $projPaths;
        }

        public function get(Request $request, Response $response) {
            $this->data['getVar'] = $request->__get('get');
<<<<<<< HEAD
            $html = $this->renderer->render('mcp', $this->data);
=======
            $html = $this->renderer->render('about', $this->data);
>>>>>>> master
            $response->body($html);
            return $response;
        }
    }
