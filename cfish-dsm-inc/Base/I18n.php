<?php
/**
 * @package  DiveSitesManager
 * @since	1.1.0
 */
namespace cfishDSMInc\Base;

class I18n extends BaseController
{

    public function register() {

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
    }

 	public function load_textdomain() {
	  load_plugin_textdomain('dive-sites-manager', false, 'dive-sites-manager/languages');
    }
}