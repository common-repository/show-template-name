<?php

use Bubuku\Plugins\ShowTemplateName\Plugin;

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die( "Hello, Pepiño!" );
}

require( __DIR__ . '/src/class-plugin.php' );

$plugin = new Plugin();
$plugin();