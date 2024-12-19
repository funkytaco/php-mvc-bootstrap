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

    public function previewTemplate(Request $request, Response $response) {
        $data = json_decode($request->body(), true);
        
        if (!isset($data['template']) || !isset($data['variables'])) {
            $response->code(400);
            return $response->json(['error' => 'Missing template or variables']);
        }

        try {
            // Convert variables array to key-value pairs for rendering
            $templateData = [];
            foreach ($data['variables'] as $variable) {
                if (isset($variable['name']) && isset($variable['value'])) {
                    $templateData[$variable['name']] = $variable['value'];
                }
            }

            // Render the template using Mustache
            $rendered = $this->renderer->render($data['template'], $templateData);
            return $response->json(['rendered' => $rendered]);
        } catch (\Exception $e) {
            $response->code(500);
            return $response->json(['error' => $e->getMessage()]);
        }
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

    private function validateTemplate(array $data): void {
        if (empty($data['name'])) {
            throw new \Exception('Template name is required');
        }
        if (empty($data['content'])) {
            throw new \Exception('Template content is required');
        }
    }

    private function updateTemplate(string $id, array $data): void {
        try {
            $this->conn->beginTransaction();

            // Update template
            $sql = "UPDATE templates SET name = ?, content = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$data['name'], $data['content'], $id]);

            // Delete existing variables
            $sql = "DELETE FROM template_variables WHERE template_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);

            // Insert new variables
            if (!empty($data['variables'])) {
                $sql = "INSERT INTO template_variables (template_id, name, default_value, type) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                
                foreach ($data['variables'] as $variable) {
                    $stmt->execute([
                        $id,
                        $variable['name'],
                        $variable['default_value'],
                        $variable['type']
                    ]);
                }
            }

            $this->conn->commit();
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    private function getTemplateById(string $id): ?array {
        try {
            // Get template data
            $sql = "SELECT t.*, GROUP_CONCAT(
                        JSON_OBJECT(
                            'name', tv.name,
                            'default_value', tv.default_value,
                            'type', tv.type
                        )
                    ) as variables
                    FROM templates t
                    LEFT JOIN template_variables tv ON t.id = tv.template_id
                    WHERE t.id = ?
                    GROUP BY t.id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $template = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$template) {
                return null;
            }

            // Parse variables JSON
            if ($template['variables']) {
                $template['variables'] = array_map(
                    fn($var) => json_decode($var, true),
                    explode(',', $template['variables'])
                );
            } else {
                $template['variables'] = [];
            }

            return $template;
        } catch (\Exception $e) {
            error_log("Error fetching template: " . $e->getMessage());
            return null;
        }
    }
}
