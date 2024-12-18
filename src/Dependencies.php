<?php

use DI\ContainerBuilder;
use Klein\Request;
use Klein\Response;
use Klein\Klein;
use Main\Renderer\Renderer;
use Main\Renderer\MustacheRenderer;
use Main\Modules\Date_Module;
use Main\Modules\MCP_Module;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->useAttributes(true);

// Configure the container
$containerBuilder->addDefinitions([
    // Renderer interface to implementation
    Renderer::class => \DI\get(MustacheRenderer::class),

    // Klein components
    Request::class => \DI\create(),
    Response::class => \DI\create(),
    Klein::class => \DI\create(),

    // Modules
    Date_Module::class => \DI\create(),
    MCP_Module::class => \DI\create(),

    // Mustache configuration
    Mustache_Engine::class => \DI\factory(function () {
        $options = ['extension' => '.html'];
        
        try {
            return new Mustache_Engine([
                'loader' => new Mustache_Loader_FilesystemLoader(VIEWS_DIR, $options),
                'partials_loader' => new Mustache_Loader_FilesystemLoader(VIEWS_DIR, $options)
            ]);
        } catch (Exception $e) {
            if (stristr($e->getMessage(), "FilesystemLoader baseDir must be a directory") !== false) {
                throw new Exception("VIEWS_DIR does not exist. To install a default template run:\ncomposer install-mvc\n");
            } else {
                throw new Exception($e->getMessage());
            }
        }
    }),

    // PDO configuration
    PDO::class => \DI\factory(function () use ($config) {
        return new PDO(
            $config['pdo']['dsn'],
            $config['pdo']['username'],
            $config['pdo']['password'],
            $config['pdo']['options']
        );
    })
]);

try {
    $container = $containerBuilder->build();
    return $container;
} catch (Exception $e) {
    throw new Exception("Error building container: " . $e->getMessage());
}
