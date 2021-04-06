<?php
/**
 * @var SimpleJsonLd\Admin\Page $page
 * @var string $group
 */

?>
<div class="wrap">
	<h1><?= $page->title; ?></h1>
	<hr>
	<form method="post" action="options.php">
		<?php settings_fields( $group ); ?>
		<?php do_settings_sections( $group ); ?>
		<?php submit_button(); ?>
	</form>
</div>
