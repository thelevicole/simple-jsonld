<?php

namespace SimpleJsonLd\Http\Controllers\Admin;

use SimpleJsonLd\Config;
use SimpleJsonLd\Http\Controllers\BaseController;
use SimpleJsonLd\Resources\View;
use SimpleJsonLd\Admin\Page;
use SimpleJsonLd\Utilities\Prefix;

class SettingsController extends BaseController {

	/**
	 * @var string Option group used by Settings API. Prefixed on instance construct.
	 */
	public $option_group = 'options-group';

	public function __construct() {

		// Prefix option group vlaue.
		$this->option_group = Prefix::prefix( $this->option_group );

		// Register admin menu/page
		$this->registerAdminPage();

		add_action( 'admin_init', [ $this, 'registerSettings' ] );
	}

	/**
	 * Create admin page
	 *
	 * @returns Page
	 */
	public function registerAdminPage() {
		$page = new Page( __( 'Simple JSON+LD', 'skape' ) );

			$view = new View( 'admin/settings.php', [
				'page' => $page,
				'group' => $this->option_group
			] );

			$page->setSlug( 'settings' );
			$page->setParent( 'options-general.php' );
			$page->setCapabilites( 'manage_options' );

			$page->setView( $view );

		$page->register();

		return $page;
	}

	/**
	 * Register our settings, sections and fields
	 */
	public function registerSettings() {
		register_setting( $this->option_group, Prefix::prefix( 'post_types' ) );

		add_settings_section(
			'field', // Section ID
			__( 'Field settings', 'skape' ),
			'__return_null', // Add instructions if need be
			$this->option_group // Page
		);

		add_settings_field(
			'post_types',
			__( 'Post types', 'skape' ) . '<p class="description" style="font-weight: normal;">' . __( 'Select the post types that JSON+LD field should appear on.', 'skape' ) . '</p>',
			[ $this, 'renderPostTypesField' ],
			$this->option_group,
			'field' // Section ID
		);
	}

	public function renderPostTypesField() {
		$values = Config::get( 'post_types' );
		$types = get_post_types( [], 'objects' );

		foreach ( $types as $type ) {
			$id = Prefix::prefix( $type->name );
			$checked = in_array( $type->name, $values );
			?>
				<label for="<?= $id; ?>" style="display: block; margin: 3px 0;">
					<input id="<?= $id; ?>" type="checkbox" name="<?= Prefix::prefix( 'post_types' ); ?>[]" <?= $checked ? 'checked' : ''; ?> value="<?= $type->name; ?>" autocomplete="off">
					<span><?= $type->label; ?><?= !$type->public ? '<em style="color: #999;">(' . __( 'Not a public type', 'skape' ) . ')</em>' : ''; ?></span>
				</label>
			<?php
		}
	}

}
