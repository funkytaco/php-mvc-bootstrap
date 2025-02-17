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
                            //$variables[$name] = $this->renderer->renderPartial($name, []);
                            $variables[$name] = $this->renderer->renderString($name, []);
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
        try {
            $templateId = $request->param('id');
            $data = json_decode($request->body(), true)['template'];
            
            $this->conn->beginTransaction();
            
            // Update template basic info
            $stmt = $this->conn->prepare("UPDATE templates SET name = ?, content = ?, type = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$data['name'], $data['content'], $data['type'], $templateId]);
            
            // Delete existing variables
            $stmt = $this->conn->prepare("DELETE FROM template_variables WHERE template_id = ?");
            $stmt->execute([$templateId]);
            
            // Insert new variables if they exist
            if (!empty($data['variables'])) {
                $stmt = $this->conn->prepare("INSERT INTO template_variables (id, template_id, name, default_value, tag_type, data_type, helper_name, arguments) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                
                foreach ($data['variables'] as $variable) {
                    $defaultValue = null;
                    $helperName = null;
                    $arguments = null;
                    
                    switch ($variable['tag_type']) {
                        case 'helper':
                            $helperName = $variable['helper_name'];
                            $arguments = json_encode($variable['arguments']);
                            break;
                        case 'section':
                        case 'inverted':
                            break;
                        case 'partial':
                            break;
                        default:
                            $defaultValue = $variable['default_value'] ?? null;
                    }
                    
                    $stmt->execute([
                        $this->generateUUID(),
                        $templateId,
                        $variable['name'],
                        $defaultValue,
                        $variable['tag_type'],
                        $variable['data_type'] ?? null,
                        $helperName,
                        $arguments
                    ]);
                }
            }
            
            $this->conn->commit();
            return $response->json(['success' => true]);
            
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return $response->json([
                'error' => 'Failed to save template: ' . $e->getMessage()
            ])->code(500);
        }
    }

    private function getTemplates(): array {
        $sql = "SELECT * FROM templates ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $templates = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Add type-based boolean flags for Mustache template
        foreach ($templates as &$template) {
            $template['isPage'] = $template['type'] === 'page';
            $template['isLayout'] = $template['type'] === 'layout';
            $template['isPartial'] = $template['type'] === 'partial';
            // Set active state if needed (can be updated based on current template ID)
            $template['active'] = false;
        }
        return $templates;
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
            $sql = "SELECT t.*,
                COALESCE(
                    array_to_json(
                        array_agg(
                            json_build_object(
                                'name', tv.name,
                                'default_value', tv.default_value,
                                'tag_type', tv.tag_type,
                                'data_type', tv.data_type,
                                'helper_name', tv.helper_name,
                                'arguments', tv.arguments::json
                            )
                        ) FILTER (WHERE tv.name IS NOT NULL)
                    ),
                    '[]'
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
            $template['variables'] = json_decode($template['variables'], true) ?: [];

            return $template;
        } catch (\Exception $e) {
            error_log("Error fetching template: " . $e->getMessage());
            return null;
        }
    }
}
