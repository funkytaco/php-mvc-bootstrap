<?php

namespace Main\Controllers;

use \Klein\Request;
use \Klein\Response;

Interface IController
{
    public function get(Request $request, Response $response);
}