<?php

namespace SimpleJsonLd\Http\Controllers;

use SimpleJsonLd\Config;
use SimpleJsonLd\Http\Controllers\BaseController;
use SimpleJsonLd\Utilities\Prefix;
use SimpleJsonLd\Wrappers\Hooks;
use SimpleJsonLd\JsonLdField;

class FrontendController extends BaseController {

	public function __construct() {
		add_action( 'wp_head', [ $this, 'printJsonLdValues' ] );
	}

	public function printJsonLdValues() {
		if ( is_singular() && in_array( get_post_type(), Config::get( 'post_types' ) ) ) {
			$field = new JsonLdField( get_the_ID() );
			echo $field->format_value();
		}
	}

}
