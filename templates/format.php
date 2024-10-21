<div class="wrap">
	<h1><?php _e('Display Format', 'dive-sites-manager'); ?></h1>
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
		<?php 
			settings_fields( 'dive_sites_format_settings' );
			do_settings_sections( 'dive_sites_format' );
			submit_button();
		?>
	</form>
			

</div>