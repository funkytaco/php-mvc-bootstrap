<?php
//echo VIEWS_DIR;exit;
$injector = new \Auryn\Injector;
$mustache_options =  array('extension' => '.html');

$injector->alias('Main\Renderer\Renderer', 'Main\Renderer\MustacheRenderer');
$injector->alias('\Klein\Request','\Klein\Request');
$injector->alias('\Klein\Response','\Klein\Response');


try {

    $injector->define('Mustache_Engine', [
        ':options' => [
    	    'loader'          => new Mustache_Loader_FilesystemLoader( VIEWS_DIR,
            $mustache_options),
           	'partials_loader'          => new Mustache_Loader_FilesystemLoader( VIEWS_DIR,
            $mustache_options),

        ],
    ]);

} catch (Exception $e) {

    if (stristr($e->getMessage(),"FilesystemLoader baseDir must be a directory") == TRUE) {
        throw new Exception("VIEWS_DIR does not exist. To install a Bootstrap template run:\n
        composer install-bootstrap\n");
    }
}









return $injector;
