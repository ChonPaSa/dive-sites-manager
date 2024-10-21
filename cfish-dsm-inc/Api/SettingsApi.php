<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Api;

class SettingsApi
{
	public $admin_pages = array();

	public $admin_subpages = array();

	public $settings = array();

	public $sections = array();

	public $fields = array();

	public function register()
	{
		if ( ! empty($this->admin_pages) || ! empty($this->admin_subpages) ) {
			add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
		}

		if ( !empty($this->settings) ) {
			add_action( 'admin_init', array( $this, 'registerCustomFields' ) );
		}
	}

	public function addPages( array $pages )
	{
		$this->admin_pages = $pages;

		return $this;
	}

	public function withSubPage( string $title = null ) 
	{
		if ( empty($this->admin_pages) ) {
			return $this;
		}

		$admin_page = $this->admin_pages[0];

		$subpage = array(
			array(
				'parent_slug' => $admin_page['menu_slug'], 
				'page_title' => __($admin_page['page_title'], 'dive-sites-manager'), 
				'menu_title' => ($title) ? __($title, 'dive-sites-manager') : __($admin_page['menu_title'], 'dive-sites-manager'), 
				'capability' => $admin_page['capability'], 
				'menu_slug' => $admin_page['menu_slug'], 
				'callback' => $admin_page['callback']
			)
		);

		$this->admin_subpages = $subpage;

		return $this;
	}

	public function addSubPages( array $pages )
	{
		$this->admin_subpages = array_merge( $this->admin_subpages, $pages );

		return $this;
	}

	public function addAdminMenu()
	{
		foreach ( $this->admin_pages as $page ) {
			add_menu_page( __($page['page_title'], 'dive-sites-manager'), __($page['menu_title'], 'dive-sites-manager'), $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}

		foreach ( $this->admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], __($page['page_title'], 'dive-sites-manager'), __($page['menu_title'],'dive-sites-manager'), $page['capability'], $page['menu_slug'], $page['callback'] );
		}
	}

	public function setSettings( array $settings )
	{
		$this->settings = $settings;

		return $this;
	}

	public function setSections( array $sections )
	{
		$this->sections = $sections;

		return $this;
	}

	public function setFields( array $fields )
	{
		$this->fields = $fields;

		return $this;
	}

	public function registerCustomFields()
	{
		// register setting
		foreach ( $this->settings as $setting ) {
			register_setting( $setting["option_group"], $setting["option_name"], ( array( "sanitize_callback" => $setting["sanitize_callback"]) ) );
		}

		// add settings section
		foreach ( $this->sections as $section ) {
			add_settings_section( $section["id"], __($section["title"], 'dive-sites-manager'), ( isset( $section["callback"] ) ? $section["callback"] : '' ), $section["page"] );
		}

		// add settings field
		foreach ( $this->fields as $field ) {
			add_settings_field( $field["id"], _x($field["title"], 'Field Title', 'dive-sites-manager'), ( isset( $field["callback"] ) ? $field["callback"] : '' ), $field["page"], $field["section"], ( isset( $field["args"] ) ? $field["args"] : '' ) );
		}

	}
}