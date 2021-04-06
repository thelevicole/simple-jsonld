<?php

namespace SimpleJsonLd\Http\Controllers\Admin;

use SimpleJsonLd\Config;
use SimpleJsonLd\Http\Controllers\BaseController;
use SimpleJsonLd\Resources\AdminEnqueuer;
use SimpleJsonLd\Resources\View;
use SimpleJsonLd\Utilities\Prefix;
use SimpleJsonLd\Wrappers\Hooks;
use SimpleJsonLd\JsonLdField;

class PostTypeController extends BaseController {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'registerMetabox' ] );
		add_action( 'save_post', [ $this, 'storeFieldValue' ] );
		JsonLdField::enqueue_scripts();
	}


	/**
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 */
	public function registerMetabox() {
		add_meta_box( Prefix::prefix( 'field' ), __( 'Structured data', 'skape' ), [ $this, 'metaboxField' ], Config::get( 'post_types' ), 'normal', 'low' );
	}

	/**
	 * Render JSON+LD field metabox
	 *
	 * @returns void
	 */
	public function metaboxField() {
		global $post;
		$view = new View( 'admin/metaboxes/field.php', [
			'field' => new JsonLdField( get_the_ID() )
		] );
		$view->render();
	}

	/**
	 * Store post meta on save.
	 *
	 * @param int $post_id
	 */
	public function storeFieldValue( $post_id ) {

		// Bail early if nonce is not valid.
		if ( empty( $_POST[ Prefix::prefix( 'csrf' ) ] ) || !wp_verify_nonce( $_POST[ Prefix::prefix( 'csrf' ) ],'store-value' ) ) {
			return;
		}

		// Bail early if not an allowed post type
		if ( !in_array( get_post_type( $post_id ), Config::get( 'post_types' ) ) ) {
			return;
		}

		$value = !empty( $_POST[ Prefix::prefix( JsonLdField::$meta_key ) ] ) ? $_POST[ Prefix::prefix( JsonLdField::$meta_key ) ] : null;

		if ( !empty( $value ) ) {
			$field = new JsonLdField( $post_id );
			$valid = $field->validate_value( $value );

			if ( $valid && !is_wp_error( $valid ) ) {
				$response = $field->store_value( $value );
			}
		}
	}

}
