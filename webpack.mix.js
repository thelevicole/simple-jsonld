const mix = require( 'laravel-mix' );

/**
 * Build preferences
 */
mix
	.setPublicPath( './build' )
	.options( { processCssUrls: false } );


/**
 * Javascripts
 */
mix
	.js( 'assets/js/admin/input.js', 'js/admin' );

/**
 * Stylesheets
 */
mix
	.sass( 'assets/scss/admin/input.scss', 'css/admin' )
