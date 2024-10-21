<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Pages;

use cfishDSMInc\Api\SettingsApi;
use cfishDSMInc\Base\BaseController;
use cfishDSMInc\Api\Callbacks\AdminCallbacks;
use cfishDSMInc\Api\Callbacks\ManagerCallbacks;

class Dashboard extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_mngr;

	public $pages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_mngr = new ManagerCallbacks();

		$this->setPages();

		$this->setSettings();
		$this->setSections();
		$this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( __('Dashboard', 'dive-sites-manager' ) )->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => __('Dive Sites Manager', 'dive-sites-manager'), 
				'menu_title' => __('Dive Sites Manager', 'dive-sites-manager'), 
				'capability' => 'manage_options', 
				'menu_slug' => 'dive_sites_manager', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => 'dashicons-pressthis', 
				'position' => 110
			)
		);
	}

	public function setSettings()
	{
		$args = array(
			array(
				'option_group' => 'dive_sites_manager_settings',
				'option_name' => 'cfish_dsm',
				'sanitize_callback' => array( $this->callbacks, 'validate_options' )
			)
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'dive_sites_manager_admin_index',
				'title' => __('Options Manager', 'dive-sites-manager'),
				'callback' => array( $this->callbacks_mngr, 'adminSectionManager' ),
				'page' => 'dive_sites_manager'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array();

		foreach ( $this->managers as $key => $value ) {
			$args[] = array(
				'id' => $key,
				'title' => $value['title'],
				'callback' => array( $this->callbacks_mngr, 'checkboxField' ),
				'page' => 'dive_sites_manager',
				'section' => 'dive_sites_manager_admin_index',
				'args' => array(
					'option_name' => 'cfish_dsm',
					'label_for' => $key,
					'description' => $value['description'],
					'class' => 'ui-toggle'
				)
			);
		}

		$this->settings->setFields( $args );
	}
}