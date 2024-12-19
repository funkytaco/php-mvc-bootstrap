<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Small;
use Main\Renderer\MustacheRenderer;
use Main\Mock\PDO;
use App\ControllerInterface;
use Main\Modules\Date_Module;

#[Small]
class IndexControllerTest extends TestCase
{
    private $IndexCtrl;

    public function setUp(): void {
        $renderer = new MustacheRenderer(new \Mustache_Engine());
        $conn = new PDO();
        $date_module = new Date_Module();
        $this->IndexCtrl = new \IndexController($renderer, $conn, $date_module);
    }

    public function tearDown(): void {}

    #[Test]
    public function testInstanceOfIController(): void {
        $this->assertInstanceOf(ControllerInterface::class, $this->IndexCtrl);
    }


}
