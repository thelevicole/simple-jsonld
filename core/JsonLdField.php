<?php

namespace SimpleJsonLd;

use SimpleJsonLd\Resources\AdminEnqueuer;
use WP_Error;
use SimpleJsonLd\Utilities\Prefix;
use SimpleJsonLd\Utilities\Html;

class JsonLdField {

	public $value = '';
	public $post_id = 0;

	public static $dom_class = 'input';
	public static $meta_key = 'value';
	public static $format = 'ld+json';

	/**
	 * JsonLdField constructor.
	 *
	 * @param int $post_id
	 */
	public function __construct( int $post_id ) {
		$this->post_id = $post_id;
		$this->value = get_post_meta( $this->post_id, Prefix::prefix( self::$meta_key ), true );
	}

	/**
	 * Enqueue admin scripts.
	 */
	public static function enqueue_scripts() {
		$enqueuer = new AdminEnqueuer;
		$enqueuer->addStyle( 'build/css/admin/input.css', [] );
		$enqueuer->addScript( 'build/js/admin/input.js', [ 'jquery' ], true, [
			'l10n' => [],
			'dom_class' => Prefix::prefix( self::$dom_class ),
			'format' => self::$format
		] );
		$enqueuer->registerAndEnqueue();
	}

	/**
	 * Render admin field.
	 */
	public function render() {
		$id = Prefix::prefix( 'textarea' );
		?>
			<label for="<?= $id; ?>" style="font-weight: bold;"><?= __( 'JSON+LD value', 'skape' ); ?></label>
			<p class="description"><?= __( 'Structured data is a standardised format for providing information about a page and classifying the page content. This should be provided as a valid JSON+LD.', 'skape' ); ?></p>
			<textarea <?= Html::escAttributes( [
				'class' => Prefix::prefix( self::$dom_class ),
				'name' => Prefix::prefix( self::$meta_key ),
				'id' => $id,
				'autocomplete' => 'off'
			] ); ?>><?= esc_textarea( $this->value ); ?></textarea>
			<?php wp_nonce_field( 'store-value', Prefix::prefix( 'csrf' ) ); ?>
		<?php
	}

	/**
	 * @param string $value
	 */
	public function store_value( $value ) {
		$this->value = $value;
		return update_post_meta( $this->post_id, Prefix::prefix( self::$meta_key ), $this->value );
	}

	/**
	 * Format value into a printable script element.
	 *
	 * @return null|string
	 */
	public function format_value() {
		$valid = $this->validate_value( $this->value );

		if ( $valid && !is_wp_error( $valid ) ) {
			return '<script type="application/' . self::$format . '" class="' . Html::escClasses( Prefix::prefix( 'structured-data' ) ) . '">' . $this->value . '</script>';
		} else if ( is_wp_error( $valid ) && current_user_can( 'administrator' ) ) {
			return '<!-- Admin only notice: Could not print structured data because... ' . implode( '; ', $valid->get_error_messages() ) . ' -->';
		}

		return null;
	}

	/**
	 * Check if the passed value is valid, false if no value. WP_Error otherwise.
	 *
	 * @param null|string $value
	 * @return bool|WP_Error
	 */
	function validate_value( ?string $value ) {

		if ( !empty( $value ) ) {
			$decoded = json_decode( wp_unslash( $value ) );

			if ( $decoded === null && json_last_error() !== JSON_ERROR_NONE ) {
				switch ( json_last_error() ) {
					case JSON_ERROR_DEPTH:
						$error = __( 'Maximum stack depth exceeded.', 'skape' );
						break;
					case JSON_ERROR_STATE_MISMATCH:
						$error = __( 'Underflow or the modes mismatch.', 'skape' );
						break;
					case JSON_ERROR_CTRL_CHAR:
						$error = __( 'Unexpected control character found.', 'skape' );
						break;
					case JSON_ERROR_SYNTAX:
						$error = __( 'Syntax error, malformed JSON.', 'skape' );
						break;
					case JSON_ERROR_UTF8:
						$error = __( 'Malformed UTF-8 characters, possibly incorrectly encoded.', 'skape' );
						break;
					default:
						$error = __( 'Unknown error.', 'skape' );
						break;
				}

				return new WP_Error( 'invalid', sprintf( __( 'This value is not a valid JSON+LD. Error: %s', 'skape' ), $error ) );
			}

			return true;
		}

		return false;
	}

}
