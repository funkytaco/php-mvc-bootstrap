<?php

    $forbidden = function() {
        echo 'forbidden';
    };

    $demo_controller = new Main\Controllers\DemoController($request, $response, $renderer, $conn);

    $return_asset_files = include('MimeTypes.php');

    return [

        //Bootstrap Demos
        ['GET', '/demos/dashboard', [$demo_controller, 'dashboardDemo']],
        ['GET', '/demos/jumbotron', [$demo_controller, 'jumbotronDemo']],
        ['GET', '/demos/cover', [$demo_controller, 'coverDemo']],
        //demo endpoints requiring further setup
        ['GET', '/demos/simplesidebar', [$demo_controller, 'simpleSidebarDemo']],
        ['GET', '/demos/vanilla', [$demo_controller, 'vanillaDemo']],

        //Asset Files - do not change unless you know what you are doing - defined in MimeTypes.php
        ['GET', '@\.(css|eot|js|json|less|svg|ttf|woff|woff2|md)$', $return_asset_files],

        //Index Page
        ['GET', '/', [$demo_controller, 'get']],
        ['OPTIONS', null, $forbidden],
        //Post
        //catchall
        ['GET', '/[*:catchall]', function() { return ''; } ],


    ];