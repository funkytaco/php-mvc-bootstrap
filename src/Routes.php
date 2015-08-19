<?php

    $forbidden = function() {
        echo 'forbidden';
    };

    $return_asset_files = include(MIMETYPES_FILE);

    /*** DO NOT MODIFY - See Bootstrap.php. Put custom routes in CUSTOM_ROUTES_FILE **/
    return [
        //Asset Files - do not change unless you know what you are doing - defined in MimeTypes.php
        ['GET', '@\.(css|eot|js|json|less|jpg|bmp|png|svg|ttf|woff|woff2|md)$', $return_asset_files],
        //Index Page
         ['OPTIONS', null, $forbidden],
        //catchall
        ['GET', '/[*:catchall]', function() { return 'NULL'; } ],
    ];
