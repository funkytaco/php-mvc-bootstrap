<?php

    $forbidden = function() {
        echo 'forbidden';
    };

    $return_asset_files = function ($request, $response, $service) {

            $filetype = $request->paramsNamed()[1];
            $filepath = 'public'. $request->pathname();
            $mimetype = null;

            switch ($filetype) {
                case 'css':
                $mimetype = 'text/css';
                break;

                case 'eot':
                $mimetype = 'application/vnd.ms-fontobject';
                break;

                case 'js':
                $mimetype = 'application/javascript';
                break;
                
                case 'json':
                $mimetype = 'application/json';
                break;
                
                case 'less':
                $mimetype = 'text/plain';
                break;
                
                case 'svg':
                $mimetype = 'image/svg+xml';
                break;
                
                case 'ttf':
                $mimetype = 'application/octet-stream';
                break;
                
                case 'woff':
                $mimetype = 'application/font-woff';
                break;
                
                case 'woff2':
                $mimetype = 'application/font-woff2';
                break;
                
                case 'md':
                $mimetype = 'text/plain';
                break;
                
                default:
                $mimetype = 'text/plain';
            }

            if (is_file($filepath)) {
                $response->file($filepath,null, $mimetype);
            } 


    };

    $demo_controller = new Main\Controllers\DemoController($request, $response, $renderer, $db);

    return [

        //Bootstrap Demos
        ['GET', '/demos/dashboard', [$demo_controller, 'dashboardDemo']],
        ['GET', '/demos/jumbotron', [$demo_controller, 'jumbotronDemo']],
        ['GET', '/demos/cover', [$demo_controller, 'coverDemo']],
        //demo endpoints requiring further setup
        ['GET', '/demos/simplesidebar', [$demo_controller, 'simpleSidebarDemo']],
        ['GET', '/demos/vanilla', [$demo_controller, 'vanillaDemo']],

        //Asset Files - do not change unless you know what you are doing
        ['GET', '@\.(css|eot|js|json|less|svg|ttf|woff|woff2|md)$', $return_asset_files],

        //Index Page
        ['GET', '/', [$demo_controller, 'get']],
        ['OPTIONS', null, $forbidden],
        //Post
        //catchall
        ['GET', '/[*:catchall]', function() { return ''; } ],


    ];