<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata;

/*
 * Plugin Name: Display Metadata
 * Plugin URI: https://github.com/trasweb/DisplayMetadata
 * Description: Show post/term/user properties and metadata in a metabox in its creation/edition form.
 * Version: 0.1
 * Author: Manuel Canga
 * Author URI: https://manuelcanga.dev
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: display-metadata
 * Domain Path: /languages
*/

if ( !defined( "ABSPATH" ) ) {
	die( "Hello, World!" );
}

if ( !is_admin() ) {
	return;
}

( function () {
	require __DIR__ . '/class-plugin.php';

	${'display-metadata'} = new Plugin( $_GET ?: [] );

	add_action( 'admin_init', ${'display-metadata'} );
} )();