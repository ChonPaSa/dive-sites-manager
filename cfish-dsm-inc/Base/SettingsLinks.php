<?php
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Base;

use cfishDSMInc\Base\BaseController;

class SettingsLinks extends BaseController
{
	public function register() 
	{
		add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
	}

	public function settings_link( $links ) 
	{
		$settings_link = '<a href="admin.php?page=dive_sites_manager">' . __('Settings', 'dive-sites-manager') . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}
}