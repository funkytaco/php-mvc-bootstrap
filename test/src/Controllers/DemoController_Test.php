<?php
class DemoControllerTest extends PHPUnit_Framework_TestCase
{

    public function setup() {

        $request = new \Klein\Request();
        $response = new \Klein\Response();
        $renderer = new Main\Renderer\MustacheRenderer(new Mustache_Engine);
        $conn = new \Main\Mock\PDO;
        $this->demo_controller = new Main\Controllers\DemoController($request, $response, $renderer, $conn);

    }

    public function tearDown() {}

    /**
    * @small
    */
    public function testInstanceOfIController() {
        $this->assertInstanceOf('Main\Controllers\IController', $this->demo_controller);
    }


}