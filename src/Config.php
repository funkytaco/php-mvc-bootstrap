<?php

    $dbtype = 'postgres';

    switch($dbtype) {
        case 'postgres':
        $settings = [
        'dsn' => 'pgsql:dbname=clouddbpostgres;host=127.0.0.1;',
        'username' => 'lgonzalez',
        'password' => '',
        'options' => null
        ];
        break;

        case 'mysql':
        $settings = [
        'dsn' => 'mysql:dbname=clouddb;host=127.0.0.1;',
        'username' => 'root',
        'password' => '',
        'options' => null
        ];

        break;
    }

    return $settings;
