<?php
// Controllers/TemplateManagerController.php
namespace Icarus\Controllers;

use \Klein\Request;
use \Klein\Response;
use \Main\Renderer\MustacheRenderer;
use \Main\Modules\PDO_Module;

class TemplateManagerController implements \App\ControllerInterface {
    private MustacheRenderer $renderer;
    private \PDO $conn;
    private array $data;

    public function __construct(MustacheRenderer $renderer, \PDO $conn) {
        $this->renderer = $renderer;
        $this->conn = $conn;
        $this->data = [
            'title' => 'Template Editor',
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
        try {
            $data = json_decode($request->body(), true);
            //print_r($data);exit;
            // Transform variables array into appropriate structure
            $variables = [];
            foreach ($data['variables'] as $var) {
                $name = $var['name'];
                
                switch ($var['tag_type']) {
                    case 'helper':
                        // For helpers, we'll just show the function call structure
                        $variables[$name] = "{{$var['helper_name']}(" . implode(', ', $var['arguments']) . ")}";
                        break;
                        
                    case 'section':
                        // For sections, use the appropriate data type
                        switch ($var['data_type']) {
                            case 'array':
                                $variables[$name] = []; // Empty array for preview
                                break;
                            case 'boolean':
                                $variables[$name] = false;
                                break;
                            case 'object':
                                $variables[$name] = new \stdClass();
                                break;
                        }
                        break;
                        
                    case 'inverted':
                        // For inverted sections, use false or empty array
                        $variables[$name] = $var['data_type'] === 'boolean' ? true : [];
                        break;
                        
                    case 'partial':
                        // For partials, try to load the partial template
                        try {
                            $variables[$name] = $this->renderer->renderPartial($name, []);
                        } catch (\Exception $e) {
                            $variables[$name] = "<!-- Partial '$name' not found -->";
                        }
                        break;
                        
                    default:
                        // For basic variables, use the value or convert based on type
                        $value = $var['value'] ?? '';
                        switch ($var['data_type']) {
                            case 'number':
                                $variables[$name] = is_numeric($value) ? floatval($value) : 0;
                                break;
                            case 'boolean':
                                $variables[$name] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                break;
                            default:
                                $variables[$name] = (string)$value;
                        }
                }
            }
            $rendered = $this->renderer->renderString($data['template'], $variables);
            return $response->json(['rendered' => $rendered]);
        } catch (\Exception $e) {
            return $response->json([
                'error' => 'Preview error: ' . $e->getMessage()
            ])->code(400);
        }
    }

    public function saveTemplate(Request $request, Response $response) {
        $templateId = $request->param('id');
        $data = json_decode($request->body(), true);
        $content = $data['template']['content'];
        $name = $data['template']['name'];
        $type = $data['template']['type'];

        $stmt = $this->conn->prepare("UPDATE templates SET name = ?, content = ?, type = ?  WHERE id = ?");
        $stmt->execute([$name, $content, $type, $templateId]);

        return $response->json(['success' => true]);
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
                $sql = "INSERT INTO template_variables (id, template_id, name, default_value, type) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($sql);
                
                foreach ($data['variables'] as $variable) {
                    $stmt->execute([
                        $this->generateUUID(),
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

    private function generateUUID(): string {
        // Generate v4 UUID
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
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
