<?php

    include('app/Controllers/IndexController.php');

    $mod_date = $injector->make('Main\Modules\Date_Module');
    $IndexCtrl = new IndexController($renderer, $conn, $mod_date);

    return [
        //Index Page
        ['GET', '/', [$IndexCtrl, 'get']],
        ['POST', '/api/1.0/foo', [$IndexCtrl, 'createFoo']],
        ['GET', '/api/1.0/foo', [$IndexCtrl, 'readFoo']],
        ['PUT', '/api/1.0/foo', [$IndexCtrl, 'updateFoo']],
        ['DELETE', '/api/1.0/foo', [$IndexCtrl, 'deleteFoo']],
    ];
