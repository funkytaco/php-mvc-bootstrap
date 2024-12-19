<?php
namespace Test\Mock;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Small;
use Main\Mock\PDO;

#[Small]
class PDO_Test extends TestCase
{
    private PDO $conn;
    private array $mockGetUserData;

    public function setUp(): void {
        $this->conn = new PDO();
        $this->mockGetUserData = [
            ["name" => "@funkytaco"],
            ["name" => "@PatrickLouys"],
            ["name" => "@Rican7"]
        ];
    }

    #[Test]
    public function shouldBeInstanceOfMockPDO(): void {
        $this->assertInstanceOf(PDO::class, $this->conn);
    }

    #[Test]
    public function shouldReturnMockUsers(): void {
        $this->assertEquals($this->mockGetUserData, $this->conn->getUsers());
    }

    #[Test]
    public function shouldPrepareMockStatement(): void {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $this->assertInstanceOf(\PDOStatement::class, $stmt);
    }

    #[Test]
    public function shouldExecuteMockStatement(): void {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $this->assertTrue($stmt->execute());
    }

    #[Test]
    public function shouldFetchAllFromMockStatement(): void {
        $stmt = $this->conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $this->assertEquals($this->mockGetUserData, $result);
    }
}
