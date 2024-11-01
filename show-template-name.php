<?php
/*
 * Plugin Name: Show Template Name
 * Description: Plugin to see which template has assigned every page in the list.
 * Version: 1.0.2
 * Author: Luis Ruiz
 * Author URI: https://www.bubuku.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: show-template-name
 * Domain Path: /languages
*/

use Bubuku\Plugins\ShowTemplateName\Plugin;

if ( ! defined( "ABSPATH" ) ) {
	die( "Hello, Pepiño!" );
}

require( __DIR__ . '/src/class-plugin.php' );

add_action( 'init', new Plugin()  );
