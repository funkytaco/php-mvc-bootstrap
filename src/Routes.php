<?php

    $forbidden = function() {
        echo 'forbidden';
    };

    $IndexCtrl = new Main\Controllers\IndexController($renderer, $conn);

    $return_asset_files = include('MimeTypes.php');

    return [

     
        //Asset Files - do not change unless you know what you are doing - defined in MimeTypes.php
        ['GET', '@\.(css|eot|js|json|less|svg|ttf|woff|woff2|md)$', $return_asset_files],

        //Index Page
        ['GET', '/', [$IndexCtrl, 'get']],
        ['OPTIONS', null, $forbidden],
        //Post
        //catchall
        ['GET', '/[*:catchall]', function() { return ''; } ],


    ];