<?php

namespace Main\Renderer;

use Mustache_Loader_FilesystemLoader;
use Mustache_Loader_StringLoader;

class FlexibleMustacheLoader implements \Mustache_Loader
{
    private Mustache_Loader_FilesystemLoader $filesystemLoader;
    private Mustache_Loader_StringLoader $stringLoader;
    private array $stringTemplates = [];

    public function __construct(string $baseDir, array $options = [])
    {
        $this->filesystemLoader = new Mustache_Loader_FilesystemLoader($baseDir, $options);
        $this->stringLoader = new Mustache_Loader_StringLoader();
    }

    public function load($name)
    {
        // If the template exists in our string templates, use that
        if (isset($this->stringTemplates[$name])) {
            return $this->stringLoader->load($this->stringTemplates[$name]);
        }

        // Otherwise, try to load from filesystem
        try {
            return $this->filesystemLoader->load($name);
        } catch (\Mustache_Exception_UnknownTemplateException $e) {
            // If neither found, throw exception
            throw new \Mustache_Exception_UnknownTemplateException(
                "Template '$name' not found in filesystem or string templates."
            );
        }
    }

    public function addStringTemplate(string $name, string $template): void
    {
        $this->stringTemplates[$name] = $template;
    }
}
