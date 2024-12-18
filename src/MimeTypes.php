<?php

    $handle_mimetypes = function ($request, $response) {

            $filetype = $request->paramsNamed()[1];
            $filepath = PUBLIC_DIR . $request->pathname();
            $mimetype = null;

            switch ($filetype) {
                case 'css':
                $mimetype = 'text/css';
                break;

                case 'eot':
                $mimetype = 'application/vnd.ms-fontobject';
                break;

                case 'js':
                case 'mjs':  // Added support for .mjs files
                $mimetype = 'application/javascript';
                break;
                
                case 'json':
                case 'map':  // Support for .map files
                $mimetype = 'application/json';
                break;
                
                case 'less':
                case 'scss':  // Added support for .scss files
                case 'yml':   // Added support for .yml files
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

                case 'ico':  // Added support for .ico files
                $mimetype = 'image/x-icon';
                break;

                case 'png':  // Added support for .png files
                $mimetype = 'image/png';
                break;

                case 'jpg':  // Added support for .jpg files
                case 'jpeg':
                $mimetype = 'image/jpeg';
                break;
                
                default:
                $mimetype = 'text/plain';
            }

            if (is_file($filepath)) {
                $response->file($filepath,null, $mimetype);
            } 


    };

    return $handle_mimetypes;
