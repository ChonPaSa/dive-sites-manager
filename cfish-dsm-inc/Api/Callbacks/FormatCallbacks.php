<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Api\Callbacks;

use cfishDSMInc\Base\BaseController;
use cfishDSMInc\Api\Callbacks\ManagerCallbacks;

class FormatCallbacks extends BaseController
{
	public $callbacks_mngr;

    public function formatDashboard()
	{
		return require_once( "$this->plugin_path/templates/format.php" );
	}
	

	public function formatSectionManager()
	{
		_e('Choose the layout options', 'dive-sites-manager');
	}

	public function validate_options( $input ) 
	{
		$this->callbacks_mngr = new ManagerCallbacks();
		
		$output = array();

		$output['posts_width'] = $this->callbacks_mngr->validate_number ( $input['posts_width'] );
		$output['template'] = $this->callbacks_mngr->validate_radio ( $input['template'] );
		$output['columns_desktop'] = $this->callbacks_mngr->validate_number ( $input['columns_desktop'] );
		$output['img_max_width'] = $this->callbacks_mngr->validate_number ( $input['img_max_width'] );
		$output['main_color'] = $this->callbacks_mngr->validate_color ( $input['main_color'] );
		$output['icons_color'] = $this->callbacks_mngr->validate_color ( $input['icons_color'] );
		$output['text_color'] = $this->callbacks_mngr->validate_color ( $input['text_color'] );
		$output['title_description'] = $this->callbacks_mngr->validate_text ( $input['title_description'] );

		return $output;
	}

}