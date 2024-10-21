<?php 
/**
 * @package  DiveSitesManager
 * @since	1.1.0
 */
namespace cfishDSMInc\Api\Callbacks;

use cfishDSMInc\Base\BaseController;
use cfishDSMInc\Api\Callbacks\ManagerCallbacks;

class CharacteristicsCallbacks extends BaseController
{
	public $callbacks_mngr;

	public function characteristicsDashboard()
	{
		return require_once( "$this->plugin_path/templates/characteristics.php" );
	}
	
	public function characteristicsSectionManager()
	{
		_e('Create a list of the characteristics that you want to see in your dive site. E.g. Maximum Depth, Level, Dive Type,.... .', 'dive-sites-manager');
	}

	public function validate_options( $input ) 
	{
		$this->callbacks_mngr = new ManagerCallbacks();
		$output = array();
		$options = get_option( 'cfish_dsm_characteristics');

		$output['total_characteristics'] = $this->callbacks_mngr->validate_number ( $input['total_characteristics'] );

		if (isset($options['total_characteristics'])) {
			for ($i=1; $i <= $options['total_characteristics'] ; $i++) {
				$output['characteristic_' . $i] = $this->callbacks_mngr->validate_text ( $input['characteristic_' . $i] );
				$output['icon_' . $i] = $this->callbacks_mngr->validate_icon ( $input['icon_' . $i] );
			}
		}
		return $output;
	}
}