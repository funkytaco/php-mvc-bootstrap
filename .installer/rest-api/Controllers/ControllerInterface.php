<?php

use \Klein\Request;
use \Klein\Response;

Interface ControllerInterface
{
    public function get(Request $request, Response $response);
}
