<?php

    include('Controllers/IndexController.php');

    $mod_date = $injector->make('Main\Modules\Date_Module');
    $IndexCtrl = new IndexController($renderer, $conn, $mod_date);

    return [
        //Index Page
        ['GET', '/', [$IndexCtrl, 'get']],
        ['GET', '/about', [$IndexCtrl, 'getAbout']],
        ['GET', '/contact', [$IndexCtrl, 'getContact']],

    ];
