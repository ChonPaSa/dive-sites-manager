<?php 
/**
 * @package  DiveSitesManager
 * @since	1.1.0
 */
namespace cfishDSMInc\Pages;

use cfishDSMInc\Api\SettingsApi;
use cfishDSMInc\Base\BaseController;
use cfishDSMInc\Api\Callbacks\FormatCallbacks;
use cfishDSMInc\Api\Callbacks\ManagerCallbacks;

class Format extends BaseController
{
	public $settings;

	public $callbacks;

	public $callbacks_mngr;

	public $pages = array();

	public function register()
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new FormatCallbacks();

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
				'page_title' => __('Display Format', 'dive-sites-manager'), 
				'menu_title' => __('Display Format', 'dive-sites-manager'), 
				'capability' => 'manage_options', 
				'menu_slug' => 'dive_sites_format', 
				'callback' => array( $this->callbacks, 'formatDashboard' ), 
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
				'option_group' => 'dive_sites_format_settings',
				'option_name' => 'cfish_dsm_format',
				'sanitize_callback' => array( $this->callbacks, 'validate_options' ),
			)
		);

		$this->settings->setSettings( $args );
	}

	public function setSections()
	{
		$args = array(
			array(
				'id' => 'dive_sites_format_admin_index',
				'title' => __('Options Manager', 'dive-sites-manager'),
				'callback' => array( $this->callbacks, 'formatSectionManager' ),
				'page' => 'dive_sites_format'
			)
		);

		$this->settings->setSections( $args );
	}

	public function setFields()
	{
		$args = array();
		$args[] = array(
			'id' => 'posts_width',
			'title' => __('Total Width (%)', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'numberField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'posts_width',
				'description' => __('Total width of the block for all dive sites','dive-sites-manager'),
				'class' => '',
				'default' => '80',
				'max' => '100'
				)
		);
		$vertical = 'Vertical <br><img src="' . $this->plugin_url . 'assets/vertical.png">';
		$horLeft = 'Horizontal Left Align <br><img src="' . $this->plugin_url . 'assets/horizontal-left.png">';
		$horRight = 'Horizontal Right Align <br><img src="' . $this->plugin_url . 'assets/horizontal-right.png">';
		$args[] = array(
			'id' => 'template',
			'title' => __('Layout', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'radioField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'template',
				'description' => __('','dive-sites-manager'),
				'class' => 'cfish-radio-imgs',
				'values' => array ('vertical' => $vertical, 'hor-left' => $horLeft, 'hor-right' => $horRight)
				)
		);
		$args[] = array(
			'id' => 'columns_desktop',
			'title' => __('Number of columns (Desktop)', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'numberField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'columns_desktop',
				'description' => __('It sets in how many columns the dive sites will be displayed. It does not have any effect on mobile','dive-sites-manager'),
				'class' => '',
				'default' => '3',
				'max' => '5'
				)
		);
		$args[] = array(
			'id' => 'img_max_width',
			'title' => __('Max Width for the images (%)', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'numberField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'img_max_width',
				'description' => __('It sets how big is the picture related to its container','dive-sites-manager'),
				'class' => '',
				'default' => '70',
				'max' => '100'
				)
		);
		$args[] = array(
			'id' => 'main_color',
			'title' => __('Titles Color', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'colorField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'main_color',
				'description' => __('Color for the title and the short description','dive-sites-manager'),
				'class' => ''
				)
		);
		$args[] = array(
			'id' => 'icons_color',
			'title' => __('Icons Color', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'colorField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'icons_color',
				'description' => __('Color for the icons of the characteristics','dive-sites-manager'),
				'class' => ''
				)
		);
		$args[] = array(
			'id' => 'text_color',
			'title' => __('Text Color', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'colorField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'text_color',
				'description' => __('Color for text of the characteristics and description','dive-sites-manager'),
				'class' => ''
				)
		);
		$args[] = array(
			'id' => 'title_description',
			'title' => __('Description Title', 'dive-sites-manager'),
			'callback' => array( $this->callbacks_mngr, 'inputField' ),
			'page' => 'dive_sites_format',
			'section' => 'dive_sites_format_admin_index',
			'args' => array(
				'option_name' => 'cfish_dsm_format',
				'label_for' => 'title_description',
				'description' => __('Text that will be shown as title for the description section','dive-sites-manager'),
				'class' => ''
				)
		);
		$this->settings->setFields( $args );
	}

}