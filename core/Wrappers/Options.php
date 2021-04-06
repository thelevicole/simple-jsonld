<?php

namespace SimpleJsonLd\Wrappers;

use SimpleJsonLd\Contracts\Prefixer;

class Options {

	use Prefixer;

	public static function get( string $name, $default = null ) {
		return get_option( self::prefix( $name ), $default );
	}

	public static function update( string $name, $value, bool $autoload = true ) {
		return update_option( self::prefix( $name ), $value, $autoload );
	}

}
