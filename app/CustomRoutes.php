<?php

include('Controllers/IndexController.php');
include('Controllers/ContactController.php');
include('Controllers/AboutController.php');
include('Controllers/MCPController.php');

    /** switched form Auryn injector->make ...to PHP-DI  $container->get **/
    $mod_date = $container->get('Main\Modules\Date_Module');
    $mod_mcp = $container->get('Main\Modules\MCP_Module');
 
    $IndexCtrl = new IndexController($renderer, $conn, $mod_date);
    $ContactCtrl = new ContactController($renderer, $conn, $mod_date);
    $AboutCtrl = new AboutController($renderer, $conn, $mod_date);

    //Add your routes here
    return [
        ['GET', '/', [$IndexCtrl, 'get']],
        ['GET', '/about', [$AboutCtrl, 'get']],
        ['GET', '/contact', [$ContactCtrl, 'get']],
    ];
