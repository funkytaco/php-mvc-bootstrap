<?php
    // Load environment variables from .env file
    $envFile = __DIR__ . '/../database/.env';
    if (!file_exists($envFile)) {
        throw new Exception('Database .env file not found');
    }
    
    $env = parse_ini_file($envFile);
    if ($env === false) {
        throw new Exception('Failed to parse database .env file');
    }

    $dbType = 'postgres';

    $arrDbSettings = [
        'dsn' => '',
        'username' => $env['POSTGRES_USER'] ?? '',
        'password' => $env['POSTGRES_PASSWORD'] ?? '',
        'options' => null
    ];

    switch($dbType) {
        case 'postgres':
        $arrDbSettings['dsn'] = sprintf(
            'pgsql:dbname=%s;host=127.0.0.1;',
            $env['POSTGRES_DB'] ?? 'icarusdb'
        );
        break;

        case 'mysql':
        $arrDbSettings['dsn'] = 'mysql:dbname=clouddb;host=127.0.0.1;';
        break;
        default:
    }

    /** Required settings - Do Not Modify **/
    $arrRequiredSettings = [
        'name' => 'Rest API',
        'installer-name' => 'rest-api',
        'views' => 'Views',
        'controllers' => 'Controllers',
        'requires' => ['date_module']
    ];

    $arrMySettings = [];


    /*** Do Not Modify below this line **/
    $arrSettings = $arrRequiredSettings;
    $arrSettings['pdo'] = $arrDbSettings;
    $arrSettings['options'] = $arrMySettings;

    return $arrSettings;
