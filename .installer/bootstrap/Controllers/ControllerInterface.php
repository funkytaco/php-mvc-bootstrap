<?php
use \Klein\Request;
use \Klein\Response;

interface ControllerInterface {

	public function get( Request $request, Response $response);
}
