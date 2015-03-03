<?php
class MockPDOTest extends PHPUnit_Framework_TestCase
{

    public function setup() {
        $this->conn = new Main\Mock\PDO();
    }

    public function tearDown() {
    }

    /**
    * @small
    */
    public function testInstanceOfIController() {
        $this->assertInstanceOf('Main\Mock\PDO', $this->conn);
    }


}