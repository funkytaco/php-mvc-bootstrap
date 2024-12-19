<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Small;
use Main\Renderer\MustacheRenderer;
use Main\Mock\PDO;
use Main\Controllers\IndexController;
use Main\Controllers\IController;
use Mustache_Engine;

class IndexControllerTest extends TestCase
{
    private $IndexCtrl;

    public function setUp(): void {
        $renderer = new MustacheRenderer(new Mustache_Engine);
        $conn = new PDO;
        $this->IndexCtrl = new IndexController($renderer, $conn);
    }

    public function tearDown(): void {}

    #[Test]
    #[Small]
    public function testInstanceOfIController(): void {
        $this->assertInstanceOf(IController::class, $this->IndexCtrl);
    }


}
