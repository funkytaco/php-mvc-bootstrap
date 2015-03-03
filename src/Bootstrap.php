<?php /*** Bootstrap file ***/

    namespace Main;
    
    define('PUBLIC_DIR', 'public');
    define('SOURCE_DIR', 'src');
    define('VENDOR_DIR', '/../vendor');

    require __DIR__ . VENDOR_DIR .'/autoload.php';

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
            include(SOURCE_DIR . '/Static/Error.php');
        });
    }
    $whoops->register();


    /**
    * Dependency Injector
    */
    $injector = include(SOURCE_DIR . '/Dependencies.php');

    /**
    * Database Configuration
    */
    $settings = include(SOURCE_DIR . '/Config.php');

    /**
    * Pass $injector PDO configuration
    *
    */
    $injector->define('\Main\PDO', [
      ':dsn' => $settings['dsn'],
      ':username' => $settings['username'],
      ':passwd' => '',
      ':options' => null // removed this line for exception
    ]); 

    /**
    * Comment out to use an RDBMS
    *
    */
    //$conn = $injector->make('\Main\Mock\PDO');

    /**
    * Uncomment out to use an RDBMS such as MySQL/PostGres.
    *
    * Config.php settings already allow MySQL and PostGres
    * with some modification.
    * 
    * You must also "use \Main\PDO" in your controller
    * instead of "use \Main\Mock\PDO".
    */
    $conn = $injector->make('\Main\Mock\PDO'); 

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

    $routes = include(SOURCE_DIR . '/Routes.php');

    foreach ($routes as $route) {
            $router->respond($route[0], $route[1], $route[2]);
    }



    $router->dispatch();