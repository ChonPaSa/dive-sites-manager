<div class="wrap">
	<h1><?php _e('Dive Sites Manager', 'dive-sites-manager'); ?></h1>
	<?php settings_errors(); ?>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1"><?php _e('General Configuration', 'dive-sites-manager'); ?></a></li>
		<li><a href="#tab-2"><?php _e('Documentation', 'dive-sites-manager'); ?></a></li>
	</ul>

	<div class="tab-content">
		<div id="tab-1" class="tab-pane active">

			<form method="post" action="options.php">
				<?php 
					settings_fields( 'dive_sites_manager_settings' );
					do_settings_sections( 'dive_sites_manager' );
					submit_button();
				?>
			</form>
			
		</div>

		<div id="tab-2" class="tab-pane">
			<h3><?php _e('Documentation', 'dive-sites-manager'); ?></h3>
			<p><?php _e('To display the dive sites list use the following shortcode', 'dive-sites-manager'); ?></p>
			<code>[cfish-dive-sites]</code>
			<p><?php _e('You can filter the dive sites by location adding it to the shortcode', 'dive-sites-manager'); ?></p>
			<code>[cfish-dive-sites location="Marsa Alam"]</code>
			<p><?php _e('Show the map with all your locations', 'dive-sites-manager'); ?></p>
			<code>[cfish-dive-map]</code>
			<p><?php _e('Add the attribute Location to show only the dive sites of that location', 'dive-sites-manager'); ?></p>
			<code>[cfish-dive-map location="Gili"]</code>
		</div>
	</div>
</div>