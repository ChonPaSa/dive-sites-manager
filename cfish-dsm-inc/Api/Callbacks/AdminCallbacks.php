<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Api\Callbacks;

use cfishDSMInc\Base\BaseController;
use cfishDSMInc\Api\Callbacks\ManagerCallbacks;

class AdminCallbacks extends BaseController
{
	public $callbacks_mngr;

	public function adminDashboard()
	{
		return require_once( "$this->plugin_path/templates/admin.php" );
	}

	public function validate_options( $input ) 
	{
		$this->callbacks_mngr = new ManagerCallbacks();
		
		$output = array();

		foreach ( $this->managers as $key => $value ) {
			$output[$key] = $this->callbacks_mngr->validate_checkbox( $input[ $key ] );
		}

		return $output;
	}

}