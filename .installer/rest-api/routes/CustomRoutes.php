<?php

	require 'app/Controllers/IndexController.php';

	$mod_date  = $injector->make( 'Main\Modules\Date_Module' );
	$IndexCtrl = new IndexController( $renderer, $conn, $mod_date );

	return array(
		// Index Page
		array( 'GET', '/', array( $IndexCtrl, 'get' ) ),
		array( 'POST', '/api/1.0/foo', array( $IndexCtrl, 'createFoo' ) ),
		array( 'GET', '/api/1.0/foo', array( $IndexCtrl, 'readFoo' ) ),
		array( 'PUT', '/api/1.0/foo', array( $IndexCtrl, 'updateFoo' ) ),
		array( 'DELETE', '/api/1.0/foo', array( $IndexCtrl, 'deleteFoo' ) ),
	);
