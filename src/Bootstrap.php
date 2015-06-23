<?php /*** Bootstrap file ***/

    namespace Main;
    
    define('PUBLIC_DIR', 'public');
    define('SOURCE_DIR', 'src');
    define('VENDOR_DIR', '/../vendor');
    $autoload_vendor_files = __DIR__ . VENDOR_DIR .'/autoload.php';

    if (is_file($autoload_vendor_files)) {
        require $autoload_vendor_files;
    } else {
        exit('vendor directory not found. Please see README.md for install instructions.');
    }

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
    * $injector
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
    * Mock Database PDO
    * $conn
    */
    $conn = $injector->make('\Main\Mock\PDO'); //comment out to use PDO $conn below

    /**
    *
    * Or Use a Real Database via PDO
    * $conn
    *   - Config.php holds PDO settings
    *   - "use \Main\PDO" in your controller
    */

    // $conn = $injector->make('\Main\PDO'); //uncomment to use PDO!

    /**
    * HTTP Request/Response Handlers
    * $request - Request Handler
    * $response - Response Handler
    */
    $request = $injector->make('\Klein\Request');
    $response = $injector->make('\Klein\Response');

    /**
    * Templating Engine
    * $renderer
    */
    $renderer = $injector->make('Main\Renderer\Renderer');


    /**
    * App Router
    * $router
    */
    $router = $injector->make('\Klein\Klein');

    /**
    * build $routes for the router. This will change depending on
    * the PHP router you choose.
    */    
    $routes = include(SOURCE_DIR . '/Routes.php');

    foreach ($routes as $route) {
            $router->respond($route[0], $route[1], $route[2]);
    }

    $router->dispatch();