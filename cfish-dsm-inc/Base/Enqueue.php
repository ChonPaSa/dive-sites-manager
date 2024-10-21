<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Base;

use cfishDSMInc\Base\BaseController;

/**
* 
*/
class Enqueue extends BaseController
{
	public function register() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}
	

	function enqueue() {
		// enqueue all our scripts
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_media();

		if ( $this->activated( 'maps_manager' ) ) {
			wp_enqueue_script('leafletmap',  $this->plugin_url . 'assets/admin-map.min.js', array('jquery'), '1.0', true);
		}
		
		wp_enqueue_style( 'pluginstyle', $this->plugin_url . 'assets/admin-style.min.css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( 'pluginscript', $this->plugin_url . 'assets/admin-script.min.js', array( 'wp-color-picker' ));
		wp_enqueue_style( 'dashicons-picker',  $this->plugin_url . 'assets/iconpicker/css/dashicons-picker.css', array( 'dashicons' ), '1.0', false );
		wp_enqueue_script( 'dashicons-picker', $this->plugin_url . 'assets/iconpicker/js/dashicons-picker.js',   array( 'jquery'    ), '1.1', true  );
	}


}