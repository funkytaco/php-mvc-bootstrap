<?php
class MockPDOTest extends PHPUnit_Framework_TestCase
{

    public function setup() {
        $this->conn = new Main\Mock\PDO();
        $this->mockGetUserData = [array("name" => "@funkytaco"), array("name" => "@PatrickLouys"), array("name" => "@Rican7")];
    }

    public function tearDown() {
    }

    /**
    * @small
    */
    public function testInstanceOfMockPDO() {
        $this->assertInstanceOf('Main\Mock\PDO', $this->conn);
    }
    /**
    * @small
    */
    public function testMockGetUsers() {
        $this->assertEquals($this->mockGetUserData, $this->conn->getUsers());
    }


}