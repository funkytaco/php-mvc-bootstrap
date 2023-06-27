<?php
require_once 'ControllerInterface.php';

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
use \Main\Mock\PDO;
use Main\Modules\Date_Module;

	/**
	 *   NOTE that the following are injected into your controller
	 *   Renderer $renderer - Template Engine
	 *   PDO $conn - PDO
	 *   Dependency Injecting makes testing easier!
	 ***/

class ContactController implements ControllerInterface {

	private $data;
	// use DemoData;

	public function __construct(
		Renderer $renderer,
		PDO $conn, Date_Module $mod_date
	) {

		$this->renderer = $renderer;
		$this->conn     = $conn;

		$this->data = array(
			'appName'      => 'PHP-MVC Template',
			'myDateModule' => $mod_date->getDate(),
			'projectList'  => self::getLegacyProjects(),
		);
	}

	public function getLegacyProjects() {
		$projPaths = array();
		if ( is_dir( 'Legacy' ) ) {
			$paths = scandir( 'Legacy' );
			foreach ( $paths as $path ) {
				if ( is_dir( 'Legacy' . $path ) && $path != '.' && $path != '..' ) {
					$projPaths[] = $path;
				}
			}
		}
		return $projPaths;
	}

	public function get( Request $request, Response $response ) {
		$this->data['getVar'] = $request->__get( 'get' );
		$html                 = $this->renderer->render( 'contact', $this->data );
		$response->body( $html );
		return $response;

	}


}
