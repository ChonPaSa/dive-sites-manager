<?php 
/**
 * @package  DiveSitesManager
 * @since	1.1.0
 */
namespace cfishDSMInc\Pages;

use cfishDSMInc\Api\SettingsApi;
use cfishDSMInc\Base\BaseController;
use cfishDSMInc\Api\Callbacks\CharacteristicsCallbacks;
use cfishDSMInc\Api\Callbacks\ManagerCallbacks;

class Characteristics extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_mngr;

	public $pages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new CharacteristicsCallbacks();

		$this->callbacks_mngr = new ManagerCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addSubPages( $this->pages )->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => __('Dive Sites Characteristics', 'dive-sites-manager'), 
				'menu_title' => __('Dive Sites Characteristics', 'dive-sites-manager'), 
				'capability' => 'manage_options', 
				'menu_slug' => 'dive_sites_characteristics', 
				'callback' => array( $this->callbacks, 'characteristicsDashboard' ), 
				'parent_slug' => 'dive_sites_manager',
				'icon_url' => 'dashicons-pressthis', 
				'position' => 110
			)
		);
	}

	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'dive_sites_characteristics_settings',
				'option_name' => 'cfish_dsm_characteristics',
				'sanitize_callback' => array( $this->callbacks, 'validate_options' ),
			)
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'dive_sites_characteristics_admin_index',
				'title' => __('Options Manager', 'dive-sites-manager'),
				'callback' => array( $this->callbacks, 'characteristicsSectionManager' ),
				'page' => 'dive_sites_characteristics'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array();
		$args[] = array(
			'id' => 'total_characteristics',
			'title' => __('How many characteristics do you want to add to your dive sites?', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'numberField' ),
			'page' => 'dive_sites_characteristics',
			'section' => 'dive_sites_characteristics_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_characteristics',
				'label_for' => 'total_characteristics',
				'description' => __('Save the changes to start editing your characteristics"','dive-sites-manager'),
				'class' => '',
				'default' => '4',
				'max' => '10'
				)
		);

		$options = get_option( 'cfish_dsm_characteristics');
		if (isset($options['total_characteristics'])) {
			for ($i=1; $i <= $options['total_characteristics'] ; $i++) {
				$args[] = array(
					'id' => 'characteristic_' . $i,
					'title' => sprintf( __('%d. Characteristic', 'dive-sites-manager' ), $i ),
					'callback' => array( $this->callbacks_mngr, 'inputField' ),
					'page' => 'dive_sites_characteristics',
					'section' => 'dive_sites_characteristics_admin_index',
					'args' => array(
						'option_name' => 'cfish_dsm_characteristics',
						'label_for' => 'characteristic_' . $i,
						'description' => __('Name of the characteristic. E.g "Maximum Depth"','dive-sites-manager'),
						'class' => ''
						)
				);

				$args[] = array(
					'id' => 'icon_' . $i ,
					'title' => '',
					'callback' => array( $this->callbacks_mngr, 'iconField' ),
					'page' => 'dive_sites_characteristics',
					'section' => 'dive_sites_characteristics_admin_index',
					'args' => array(
						'option_name' => 'cfish_dsm_characteristics',
						'label_for' => 'icon_' . $i,
						'description' => __('This icon will be displayed next to the characteristic name"','dive-sites-manager'),
						'class' => ''
						)
				);


			}
		}

		$this->settings->setFields( $args );
	}
}