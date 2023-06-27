<?php

	require 'app/Controllers/IndexController.php';

	$mod_date  = $injector->make( 'Main\Modules\Date_Module' );
	$IndexCtrl = new IndexController( $renderer, $conn, $mod_date );

	return array(
		// Index Page
		array( 'GET', '/', array( $IndexCtrl, 'get' ) ),
		array( 'GET', '/about', array( $IndexCtrl, 'getAbout' ) ),
		array( 'GET', '/contact', array( $IndexCtrl, 'getContact' ) ),

	);
