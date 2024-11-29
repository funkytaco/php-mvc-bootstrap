<?php
// PHP Development Router for Local Development
// Mimics Apache's .htaccess routing behavior

// Serve static files directly
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . '/html' . $path;
    
    // Check if the file exists and is not a directory
    if (file_exists($file) && is_file($file)) {
        // Serve static files like CSS, JS, images
        return false;
    }
}

// Route all other requests to index.php
require_once __DIR__ . '/html/index.php';
