<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Base;

class BaseController
{
	public $plugin_path;

	public $plugin_url;

	public $plugin;

	public $managers = array();

	public function __construct() {
		$this->plugin_path = plugin_dir_path( dirname( __FILE__, 2 ) );
		$this->plugin_url = plugin_dir_url( dirname( __FILE__, 2 ) );
		$this->plugin = plugin_basename( dirname( __FILE__, 3 ) ) . '/cfish-dive-sites-manager.php';

		$this->managers = array(
			'locations_manager' => array( 
					'title' => __('Locations Manager','dive-sites-manager'),
					'description' => __('Allows you to have dive sites in diferent locations. E.g. Komodo and Maldives','dive-sites-manager')),
			'maps_manager' => array(
					'title' => __('Maps Manager','dive-sites-manager'),
					'description' =>  __('Show your locations in a map. It requires <a href="https://wordpress.org/plugins/leaflet-map/" target="_blank">Leaflet Map Plugin by bozdoz</a>','dive-sites-manager'))
		);
	}

	public function activated( string $key )
	{
		$option = get_option( 'cfish_dsm' );

		$active = isset( $option[ $key ] ) ? $option[ $key ] : false;

		if ($active && ($key == 'maps_manager')  ){

			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if( !(is_plugin_active( 'leaflet-map/leaflet-map.php' ) ) ){
				$managers = get_option( 'cfish_dsm' );
				$managers['maps_manager'] = 0;
				update_option( 'cfish_dsm', $managers );

				add_action ( 'admin_notices', array($this, 'mapPluginNotInstalled'));
			}
		}
		return $active;
	}


	public function mapPluginNotInstalled()
	{
		add_settings_error( 'dive-sites-manager-notices', 'leaflet-missing', __('Leaflet Map plugin muss be installed and active', 'dive-sites-manager'), 'error' );
	}
		

}