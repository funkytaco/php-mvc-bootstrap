<?php
class IndexControllerTest extends PHPUnit_Framework_TestCase
{

    public function setup() {

        $renderer = new Main\Renderer\MustacheRenderer(new Mustache_Engine);
        $conn = new \Main\Mock\PDO;
        $this->IndexCtrl = new Main\Controllers\IndexController($renderer, $conn);

    }

    public function tearDown() {}

    /**
    * @small
    */
    public function testInstanceOfIController() {
        $this->assertInstanceOf('Main\Controllers\IController', $this->IndexCtrl);
    }


}