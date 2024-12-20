<?php
    return [
        ['GET', '/templates', function($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->get($request, $response);
        }],
        ['GET', '/templates/[*:id]', function($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->getTemplate($request, $response);
        }],
        ['POST', '/templates/id/[*:id]', function($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->saveTemplate($request, $response);
        }],
        ['POST', '/api/templates/id/new', function($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->saveTemplate($request, $response);
        }],
        ['POST', '/template-manager/preview', function($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->previewTemplate($request, $response);
        }],
        ['GET', '/api/templates/id/[*:id]', function ($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->getTemplate($request, $response);
        }],
        ['POST', '/api/templates/[*:id]', function ($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->saveTemplate($request, $response);
        }],
        ['POST', '/api/templates/preview', function ($request, $response) use ($container) {
            return $container->get('Icarus\Controllers\TemplateManagerController')->previewTemplate($request, $response);
        }]
    ];
