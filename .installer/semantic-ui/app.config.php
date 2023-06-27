<?php

	$dbType = 'postgres';

	$arrDbSettings = array(
		'dsn'      => '',
		'username' => 'dbuser',
		'password' => '',
		'options'  => null,
	);

	switch ( $dbType ) {
		case 'postgres':
			$arrDbSettings['dsn'] = 'pgsql:dbname=clouddbpostgres;host=127.0.0.1;';
			break;

		case 'mysql':
			$arrDbSettings['dsn'] = 'mysql:dbname=clouddb;host=127.0.0.1;';
			break;
		default:
	}

	/** Required settings - Do Not Modify */
	$arrRequiredSettings = array(
		'name'           => 'Semantic UI',
		'installer-name' => 'semantic-ui',
		'views'          => 'Views',
		'controllers'    => 'Controllers',
		'requires'       => array( 'date_module' ),
	);

	/** MY SETTINGS
		specify as 'key' => 'value' */
	$arrMySettings = array();


	/*** Do Not Modify below this line */
	$arrSettings            = $arrRequiredSettings;
	$arrSettings['pdo']     = $arrDbSettings;
	$arrSettings['options'] = $arrMySettings;

	return $arrSettings;
