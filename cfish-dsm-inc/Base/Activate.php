<?php
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Base;

class Activate
{
	public static function activate() {
		flush_rewrite_rules();

		$default = array();

		if ( ! get_option( 'cfish_dsm' ) ) {
			update_option( 'cfish_dsm', $default );
		}
		
		if ( ! get_option( 'cfish_dsm_loc' ) ) {
			update_option( 'cfish_dsm_loc', $default );
		}
		
		$default = array(
			'height' => 600,
			'width' => 80,
			'icon' => '',
			'allow_zoom' => true,
			'map_tiles' => 'standard'
		);
		if ( ! get_option( 'cfish_dsm_map' ) ) {
			update_option( 'cfish_dsm_map', $default );
		}

		if ( ! get_option( 'cfish_dsm_characteristics' ) ) {
			$default = array(
				'total_characteristics' => 4,
				'characteristic_1' => 'Dive Type',
				'characteristic_2' => 'Depth',
				'characteristic_3' => 'Level',
				'characteristic_4' => 'Current',
				'icon_1' => 'dashicons-art',
				'icon_2' => 'dashicons-dashboard',
				'icon_3' => 'dashicons-universal-access-alt',
				'icon_4' => 'dashicons-menu'
			);
			update_option( 'cfish_dsm_characteristics', $default );
		}
		if ( ! get_option( 'cfish_dsm_format' ) ) {
			$default = array ( 
				'posts_width' => 85,
				'template' => 'hor-left',
				'columns_desktop' => 2,
				'img_max_width' => 100,
				'main_color' => '#282828',
				'icons_color' => '#282828',
				'text_color' => '#282828',
				'title_description'	=> 'Dive Site Description'
			);
			update_option( 'cfish_dsm_format', $default );
		}
	}
}