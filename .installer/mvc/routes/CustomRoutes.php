<?php

require 'Controllers/IndexController.php';
require 'Controllers/ContactController.php';
require 'Controllers/AboutController.php';

	$mod_date    = $injector->make( 'Main\Modules\Date_Module' );
	$IndexCtrl   = new IndexController( $renderer, $conn, $mod_date );
	$ContactCtrl = new ContactController( $renderer, $conn, $mod_date );
	$AboutCtrl   = new AboutController( $renderer, $conn, $mod_date );

	// Add your routes here
	return array(
		array( 'GET', '/', array( $IndexCtrl, 'get' ) ),
		array( 'GET', '/about', array( $AboutCtrl, 'get' ) ),
		array( 'GET', '/contact', array( $ContactCtrl, 'get' ) ),

	);
