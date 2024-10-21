<div class="wrap">
	<h1><?php _e('Characteristics for your Dive Sites', 'dive-sites-manager'); ?></h1>
	<?php settings_errors(); ?>
	<form method="post" action="options.php">
		<?php 
			settings_fields( 'dive_sites_characteristics_settings' );
			do_settings_sections( 'dive_sites_characteristics' );
			submit_button();
		?>
	</form>
			

</div>