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
    *   MCP_Module $mod_mcp - MCP Module
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
                'appName' => "PHP-MVC Template",
                'myDateModule' => $this->mod_date->getDate(),
                'projectList' => self::getLegacyProjects()
            ];
        }

        public function getLegacyProjects() {
            $projPaths = array();
            if (is_dir('Legacy')) {
                $paths = scandir('Legacy');
                foreach ($paths as $path) {
                    if (is_dir('Legacy/' . $path) && $path != '.' && $path != '..') {
                        $projPaths[] = $path;
                    }
                }
            }
            return $projPaths;
        }

        public function get(Request $request, Response $response) {
            $this->data['getVar'] = $request->__get('get');
            $html = $this->renderer->render('mcp', $this->data);
            $response->body($html);
            return $response;
        }
    }
