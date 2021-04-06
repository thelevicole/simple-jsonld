<?php

/**
 * Plugin Name: Simple structured data [DEV]
 * Description: Adds a basic structured data editor to post types and prints the JSON+LD values into the <head>.
 * Version: 1.0.0
 * Plugin URI: https://skape.co/
 * Author: Skape Collective
 * Author URI: https://skape.co/
 * Text Domain: skape
 * Network: false
 * Requires at least: 5.0.0
 * Requires PHP: 7.2
 */

require_once plugin_dir_path( __FILE__ ) . 'core/Autoload.php';
$autoload = new SimpleJsonLd\Autoload( plugin_dir_path( __FILE__ ) );

$autoload->loadArray( [
	'SimpleJsonLd\\' => 'core'
], 'psr-4' );

// Register global constants
SimpleJsonLd\Utilities\Constants::set( 'DEBUG', defined( 'WP_DEBUG' ) && WP_DEBUG );
SimpleJsonLd\Utilities\Constants::set( 'VERSION', '1.0.0' );
SimpleJsonLd\Utilities\Constants::set( 'PATH', plugin_dir_path( __FILE__ ) );
SimpleJsonLd\Utilities\Constants::set( 'URL', plugin_dir_url( __FILE__ ) );
SimpleJsonLd\Utilities\Constants::set( 'BASENAME', plugin_basename( __FILE__ ) );

// Set the allowed post types config
SimpleJsonLd\Config::set( 'post_types', SimpleJsonLd\Wrappers\Options::get( 'post_types', array_values( get_post_types( [
	'public' => true
], 'names' ) ) ) );

// Init admin
if ( is_admin() ) {
	SimpleJsonLd\Http\Controllers\Admin\AdminController::init();
} else {
	SimpleJsonLd\Http\Controllers\FrontendController::init();
}
