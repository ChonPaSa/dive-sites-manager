<?php
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
/*
Plugin Name: Dive Sites Manager
Plugin URI: https://code-fish.eu
Description: Plugin to show the information about dive sites including a map. Add characteristics, photos, description,...They can also be organized in multiple locations.
Version: 1.2.2
Author: Choni code-fish.eu
Author URI: https://code-fish.eu
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
Text Domain: dive-sites-manager
Domain Path: /languages
*/

/*
	This file is part of Dive Sites Manager.

	Dive Sites Manager is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Dive Sites Manager is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Dive Sites Manager.  If not, see <https://www.gnu.org/licenses/>.
*/

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Nope' );

// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_cfish_dive_sites_manager() {
	cfishDSMInc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_cfish_dive_sites_manager' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_cfish_dive_sites_manager() {
	cfishDSMInc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_cfish_dive_sites_manager' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'cfishDSMInc\\Init' ) ) {
	cfishDSMInc\Init::registerServices();
}

