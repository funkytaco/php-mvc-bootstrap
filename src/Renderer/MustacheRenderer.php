<?php

namespace Main\Renderer;

use Mustache_Engine;
use Mustache_Exception_UnknownTemplateException;

class MustacheRenderer implements Renderer
{
    private Mustache_Engine $engine;
    private FlexibleMustacheLoader $loader;

    public function __construct(Mustache_Engine $engine)
    {
        $this->engine = $engine;
        $this->loader = $engine->getLoader();
    }

    public function render(string $template, array $data = []): string
    {
        try {
            return $this->engine->render($template, $data);
        } catch (Mustache_Exception_UnknownTemplateException $e) {
            throw new \RuntimeException("Template not found: $template", 0, $e);
        } catch (\Exception $e) {
            throw new \RuntimeException("Error rendering template: " . $e->getMessage(), 0, $e);
        }
    }

    public function renderString(string $template, array $data = []): string
    {
        try {
            // Generate a unique template name
            $templateName = 'string_template_' . md5($template);
            
            // Add the string template to our loader
            $this->loader->addStringTemplate($templateName, $template);
            
            // Render the template
            return $this->engine->render($templateName, $data);
        } catch (\Exception $e) {
            throw new \RuntimeException("Error rendering string template: " . $e->getMessage(), 0, $e);
        }
    }
}
