<?php
// Controllers/TemplateManagerController.php
namespace Icarus\Controllers;

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\Renderer;
use \Main\Modules\PDO_Module;

class TemplateManagerController implements \App\ControllerInterface {
    private Renderer $renderer;
    private \PDO $conn;
    private array $data;

    public function __construct(Renderer $renderer, \PDO $conn) {
        $this->renderer = $renderer;
        $this->conn = $conn;
        $this->data = [
            'title' => 'Template Manager',
            'templates' => $this->getTemplates()
        ];
    }

    public function get(Request $request, Response $response) {
        $html = $this->renderer->render('template-manager/index', $this->data);
        $response->body($html);
        return $response;
    }

    public function getTemplate(Request $request, Response $response) {
        $templateId = $request->param('id');
        $template = $this->getTemplateById($templateId);
        
        if (!$template) {
            $response->code(404);
            return $response;
        }

        return $response->json($template);
    }

    public function saveTemplate(Request $request, Response $response) {
        $templateData = $request->param('template');
        $templateId = $request->param('id');
        
        try {
            $this->validateTemplate($templateData);
            $this->updateTemplate($templateId, $templateData);
            return $response->json(['success' => true]);
        } catch (\Exception $e) {
            $response->code(400);
            return $response->json(['error' => $e->getMessage()]);
        }
    }

    private function getTemplates(): array {
        $sql = "SELECT * FROM templates ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getTemplateById(string $id): ?array {
        $sql = "SELECT * FROM templates WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    private function validateTemplate(array $data): void {
        if (empty($data['name'])) {
            throw new \Exception('Template name is required');
        }
        if (empty($data['content'])) {
            throw new \Exception('Template content is required');
        }
    }

    private function updateTemplate(string $id, array $data): void {
        $sql = "UPDATE templates SET name = ?, content = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$data['name'], $data['content'], $id]);
    }
}