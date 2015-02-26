<?php /*** Bootstrap file ***/

namespace Main;

require __DIR__ . '/../vendor/autoload.php';
//include('Database/PDOWrapper.php');
include('Mock/Database/PDOWrapper.php'); //comment out or remove for production!

$environment = 'development';
//$environment = 'production';

/**
* Error Handler
*/
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        //notify devs/log error here;
        include('src/Static/Error.php');
    });
}
$whoops->register();


/**
* Dependency Injector
*/
$injector = include('Dependencies.php');

/**
* Database Layer
*
*/
//$db = $injector->make('\Main\Database\PDOWrapper');
$db = $injector->make('\Main\Mock\Database\PDOWrapper');

/**
* HTTP Request/Response Handlers
*/
$request = $injector->make('\Klein\Request');
$response = $injector->make('\Klein\Response');

/**
* Templating Engine
*/
$renderer = $injector->make('Main\Renderer\Renderer');


/**
* App Router
*/
$router = $injector->make('\Klein\Klein');

$routes = include('Routes.php');

foreach ($routes as $route) {
        $router->respond($route[0], $route[1], $route[2]);
}



$router->dispatch();