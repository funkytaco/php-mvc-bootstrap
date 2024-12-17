<?php /*** Bootstrap file ***/

namespace Main;
error_reporting(E_ALL);

define('ENV', 'development');

define('MODELS_DIR', __DIR__ . '/../app/Models');
define('VIEWS_DIR', __DIR__ . '/../app/Views');
define('CONTROLLERS_DIR', __DIR__ . '/../app/Controllers');

define('SOURCE_DIR', __DIR__);
define('VENDOR_DIR', '/../vendor');
define('PUBLIC_DIR', 'public');

define('CUSTOM_ROUTES_FILE', __DIR__ .'/../app/CustomRoutes.php');
define('CONFIG_FILE', __DIR__ . '/../app/app.config.php');
define('DEPENDENCIES_FILE', SOURCE_DIR . '/Dependencies.php');
define('MIMETYPES_FILE', SOURCE_DIR . '/MimeTypes.php');

$autoload_vendor_files = __DIR__ . VENDOR_DIR .'/autoload.php';

if (is_file($autoload_vendor_files)) {
    require $autoload_vendor_files;
} else {
    exit('<b>vendor</b> directory not found. Please see README.md for install instructions, or simply try running <b>composer install</b>.');
}

// Load PHP 8 compatibility layer
require_once __DIR__ . '/PHP8Compatibility.php';

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
* Templating Engine
* $renderer
*/
$renderer = $injector->make('Main\Renderer\Renderer');

/**
* HTTP Request/Response Handlers
* $request - Request Handler
* $response - Response Handler
*/
$request = $injector->make('\Klein\Request');
$response = $injector->make('\Klein\Response');

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
        if ($route[1] === '@\.(css|eot|js|json|less|jpg|bmp|png|svg|ttf|woff|woff2|md)$') {
            // Special handling for asset files
            $router->respond('GET', $route[1], $route[2]);
        } else {
            $router->respond($route[0], $route[1], $route[2]);
        }
    }
}
// Load custom routes if they exist
if (is_file(CUSTOM_ROUTES_FILE)) {
    $custom_routes = include(CUSTOM_ROUTES_FILE);
    if (gettype($custom_routes) == 'array') {
        foreach ($custom_routes as $route) {
            $router->respond($route[0], $route[1], $route[2]);
        }
    }
}
// Add PHP 8 compatibility patch for Klein
// if (!function_exists('klein_php8_patch')) {
//     function klein_php8_patch() {
//         if (!class_exists('ReturnTypeWillChange')) {
//             class ReturnTypeWillChange extends \Attribute
//             {
//                 public function __construct() {}
//             }
//         }
//     }
//     klein_php8_patch();
// }

// Dispatch routes
$router->dispatch();
