<?php

$injector = new \Auryn\Provider;
$mustache_options =  array('extension' => '.html');

$injector->alias('Main\Renderer\Renderer', 'Main\Renderer\MustacheRenderer');
$injector->alias('\Klein\Request','\Klein\Request');
$injector->alias('\Klein\Response','\Klein\Response');

$injector->define('Mustache_Engine', [
    ':options' => [
	    'loader'          => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/src/Views',
        $mustache_options),
       	'partials_loader'          => new Mustache_Loader_FilesystemLoader(dirname(__DIR__) . '/src/Views',
        $mustache_options),

    ],
]);







return $injector;