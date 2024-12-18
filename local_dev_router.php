<?php
/**
 * PHP Development Server Router Script
 * This file handles routing for the PHP built-in development server
 */

// Load PHP 8 compatibility layer first
require_once __DIR__ . '/src/PHP8Compatibility.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Handle static files directly
if (preg_match('/\.(?:css|eot|js|mjs|json|map|less|scss|yml|jpg|jpeg|bmp|png|svg|ttf|woff|woff2|md|ico)$/', $_SERVER["REQUEST_URI"])) {
    $filePath = __DIR__ . '/html' . $_SERVER["REQUEST_URI"];
    if (file_exists($filePath)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'mjs' => 'application/javascript',
            'json' => 'application/json',
            'map' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'svg' => 'image/svg+xml',
            'ttf' => 'application/x-font-ttf',
            'woff' => 'application/font-woff',
            'woff2' => 'application/font-woff2',
            'eot' => 'application/vnd.ms-fontobject',
            'less' => 'text/plain',
            'scss' => 'text/plain',
            'yml' => 'text/plain',
            'md' => 'text/plain',
            'ico' => 'image/x-icon',
        ];
        
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        readfile($filePath);
        return true;
    }
}

// For all other requests, route through index.php
require_once __DIR__ . '/html/index.php';
