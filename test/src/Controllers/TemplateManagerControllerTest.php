<?php

namespace Test\Controllers;

use PHPUnit\Framework\TestCase;
use Klein\Request;
use Klein\Response;
use Main\Renderer\Renderer;
use Main\Mock\PDO;
use Icarus\Controllers\TemplateManagerController;

class TemplateManagerControllerTest extends TestCase
{
    private $renderer;
    private $pdo;
    private $controller;
    private $request;
    private $response;

    protected function setUp(): void
    {
        // Mock Renderer
        $this->renderer = $this->createMock(Renderer::class);
        
        // Mock PDO with template data
        $this->pdo = $this->createMock(PDO::class);
        $this->mockPdoMethods();
        
        // Create controller instance
        $this->controller = new TemplateManagerController($this->renderer, $this->pdo);
        
        // Mock Request and Response
        $this->request = $this->createMock(Request::class);
        $this->response = $this->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        // Configure response to support method chaining
        $this->response->method('body')->willReturn($this->response);
        $this->response->method('code')->willReturn($this->response);
        $this->response->method('json')->willReturn($this->response);
    }

    private function mockPdoMethods(): void
    {
        // Mock PDO statement
        $pdoStatement = $this->createMock(\PDOStatement::class);
        
        // Mock templates data
        $templatesData = [
            [
                'id' => '1',
                'name' => 'Test Template',
                'content' => 'Test Content'
            ]
        ];

        // Configure statement mock for getTemplates
        $pdoStatement->method('fetchAll')
            ->willReturn($templatesData);
        
        // Configure statement mock for getTemplateById
        $pdoStatement->method('fetch')
            ->willReturn($templatesData[0]);

        // Configure PDO mock
        $this->pdo->method('prepare')
            ->willReturn($pdoStatement);
    }

    public function testGet(): void
    {
        // Configure mocks
        $this->renderer->expects($this->once())
            ->method('render')
            ->with('template-manager/index', $this->anything())
            ->willReturn('rendered html');

        $this->response->expects($this->once())
            ->method('body')
            ->with('rendered html');

        // Execute test
        $result = $this->controller->get($this->request, $this->response);
        
        // Assert response is returned
        $this->assertSame($this->response, $result);
    }

    public function testGetTemplate(): void
    {
        // Configure request mock for id parameter
        $this->request->expects($this->once())
            ->method('param')
            ->with('id')
            ->willReturn('1');

        // Configure response mock
        $this->response->expects($this->once())
            ->method('json')
            ->with($this->callback(function($template) {
                return $template['id'] === '1' &&
                       $template['name'] === 'Test Template' &&
                       $template['content'] === 'Test Content';
            }));

        // Execute test
        $result = $this->controller->getTemplate($this->request, $this->response);
        
        // Assert response is returned
        $this->assertSame($this->response, $result);
    }

    public function testGetTemplateNotFound(): void
    {
        // Create a new PDO mock specifically for this test
        $pdo = $this->createMock(PDO::class);
        $pdoStatement = $this->createMock(\PDOStatement::class);
        
        // Configure statement to return false (no template found)
        $pdoStatement->method('fetch')
            ->willReturn(false);
        $pdoStatement->method('execute')
            ->willReturn(true);
            
        $pdo->method('prepare')
            ->willReturn($pdoStatement);

        // Create a new controller instance with the test-specific PDO mock
        $controller = new TemplateManagerController($this->renderer, $pdo);

        // Configure request mock for id parameter
        $this->request->expects($this->once())
            ->method('param')
            ->with('id')
            ->willReturn('999');

        // Configure response mock
        $this->response->expects($this->once())
            ->method('code')
            ->with(404);

        // Execute test
        $result = $controller->getTemplate($this->request, $this->response);
        
        // Assert response is returned
        $this->assertSame($this->response, $result);
    }

    public function testSaveTemplateSuccess(): void
    {
        $templateData = [
            'name' => 'Updated Template',
            'content' => 'Updated Content'
        ];

        // Configure request mock for parameters
        $this->request->expects($this->exactly(2))
            ->method('param')
            ->willReturnCallback(function($param) use ($templateData) {
                return match($param) {
                    'template' => $templateData,
                    'id' => '1',
                    default => null
                };
            });

        // Configure response mock
        $this->response->expects($this->once())
            ->method('json')
            ->with(['success' => true]);

        // Execute test
        $result = $this->controller->saveTemplate($this->request, $this->response);
        
        // Assert response is returned
        $this->assertSame($this->response, $result);
    }

    public function testSaveTemplateValidationError(): void
    {
        $templateData = [
            'name' => '', // Invalid: empty name
            'content' => 'Content'
        ];

        // Configure request mock for parameters
        $this->request->expects($this->exactly(2))
            ->method('param')
            ->willReturnCallback(function($param) use ($templateData) {
                return match($param) {
                    'template' => $templateData,
                    'id' => '1',
                    default => null
                };
            });

        // Configure response mock
        $this->response->expects($this->once())
            ->method('code')
            ->with(400);

        $this->response->expects($this->once())
            ->method('json')
            ->with(['error' => 'Template name is required']);

        // Execute test
        $result = $this->controller->saveTemplate($this->request, $this->response);
        
        // Assert response is returned
        $this->assertSame($this->response, $result);
    }
}
