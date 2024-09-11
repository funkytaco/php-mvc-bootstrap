<?php /*** Bootstrap file ***/

    namespace Main;
    error_reporting(E_ALL);

    define('ENV', 'development');

    define('MODELS_DIR', __DIR__ . '/../app/Models');
    define('VIEWS_DIR', __DIR__ . '/../app/Views');
    define('CONTROLLERS_DIR', __DIR__ . '/../app/Controllers');
    define('SOURCE_DIR', __DIR__);
    define('VENDOR_DIR', '../vendor');
    define('PUBLIC_DIR', 'public');

    define('CUSTOM_ROUTES_FILE', __DIR__ .'/../app/CustomRoutes.php');
    define('CONFIG_FILE', __DIR__ . '/../app/app.config.php');
    define('DEPENDENCIES_FILE', SOURCE_DIR . '/Dependencies.php');
    define('MIMETYPES_FILE', SOURCE_DIR . '/MimeTypes.php');

    $autoload_vendor_files = __DIR__ .'/'. VENDOR_DIR .'/autoload.php';

    if (is_file($autoload_vendor_files)) {
        require $autoload_vendor_files;
    } else {
        exit('Unable to load '. $autoload_vendor_files .'<b>vendor</b> directory '. VENDOR_DIR .' not found. Current Dir: '. __DIR__ .' Please see README.md for install instructions, or simply try running <b>composer install</b>.');
    }

    /**
    * Error Handler
    */
    $whoops = new \Whoops\Run;
    if (ENV !== 'production') {
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
    $injector = include(DEPENDENCIES_FILE);

    /**
    * App Configuration - these are imported via the .installer directory
    */
    if (is_file(CONFIG_FILE)) {
        $config = include(CONFIG_FILE);
    } else {
        exit('App config file not found: '. CONFIG_FILE);
    }
    /**
    * Pass $injector PDO configuration
    *
    */
    $injector->define('\Main\PDO', [
      ':dsn' => $config['pdo']['dsn'],
      ':username' => $config['pdo']['username'],
      ':passwd' => $config['pdo']['password'],
      ':options' => $config['pdo']['options']
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
    *   - app.config.php holds PDO settings
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

    /**** end injector includes ***/

    /**
    * build $routes for the router. This will change depending on
    * the PHP router you choose.
    */
    $routes = include('Routes.php');

    if (gettype($routes) == 'array') {
      foreach ($routes as $route) {
              $router->respond($route[0], $route[1], $route[2]);
      }
    }

    if (is_file(CUSTOM_ROUTES_FILE)) {

        $custom_routes = include(CUSTOM_ROUTES_FILE);
        if (gettype($custom_routes) == 'array') {
          foreach ($custom_routes as $route) {
                  $router->respond($route[0], $route[1], $route[2]);
          }
        }
    }



    $router->dispatch();
