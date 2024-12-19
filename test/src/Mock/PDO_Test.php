<?php
namespace Test\Mock;

use PHPUnit\Framework\TestCase;
use Main\Mock\PDO;

class PDO_Test extends TestCase
{
    private PDO $conn;
    private array $mockGetUserData;

    public function setUp(): void {
        $this->conn = new PDO();
        $this->mockGetUserData = [
            ["name" => "@funkytaco"],
            ["name" => "@Foo"],
            ["name" => "@Bar"]
        ];
    }

    /**
     * @test
     * @small
     */
    public function shouldBeInstanceOfMockPDO() {
        $this->assertInstanceOf(PDO::class, $this->conn);
    }

    /**
     * @test
     * @small
     */
    public function shouldReturnMockUsers() {
        $this->assertEquals($this->mockGetUserData, $this->conn->getUsers());
    }

    /**
     * @test
     * @small
     */
    public function shouldPrepareMockStatement() {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
    }

    /**
     * @test
     * @small
     */
    public function shouldExecuteMockStatement() {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $this->assertTrue($stmt->execute());
    }

    /**
     * @test
     * @small
     */
    public function shouldFetchAllFromMockStatement() {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($this->mockGetUserData, $result);
    }
}
