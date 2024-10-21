<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Base;

use cfishDSMInc\Api\SettingsApi;
use cfishDSMInc\Base\BaseController;

/**
* 
*/
class DiveSitesController extends BaseController
{
	public $settings;


	public function register()
	{
		$this->settings = new SettingsApi();

	
		add_action( 'init', array( $this, 'dive_sites_cpt' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_action( 'manage_divesite_posts_columns', array( $this, 'set_custom_columns' ) );
		//add_action( 'manage_divesite_posts_custom_column', array( $this, 'set_custom_columns_data' ), 10, 2 );
		add_filter( 'manage_edit-divesite_sortable_columns', array( $this, 'set_custom_columns_sortable' ) );
		

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ));
		//Shortcode for the dive sites information
		add_shortcode( 'cfish-dive-sites', array( $this, 'cfish_dive_sites_shortcode' ) );

		//Shortcode for the dive Map
		if ( $this->activated( 'maps_manager' ) ) {
			add_shortcode( 'cfish-dive-map', array( $this, 'cfish_dive_map_shortcode' ) );
		}
	}

	public function enqueue_frontend(){
		wp_enqueue_style( 'front-style', $this->plugin_url . 'assets/front-style.min.css' );
		wp_enqueue_style( 'dashicons' );
	}


	public function cfish_dive_sites_shortcode($atts)
	{
		ob_start();

		$format = get_option( 'cfish_dsm_format');
		$template = $format['template'];
		if ($template == 'vertical'){
			require_once( "$this->plugin_path/templates/divesites-vertical.php" );
		}
		if ($template == 'hor-left'){
			require_once( "$this->plugin_path/templates/divesites-horizontal-left.php" );
		}
		if ($template == 'hor-right'){
			require_once( "$this->plugin_path/templates/divesites-horizontal-right.php" );		
		}

		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	

	/**
	 * @since	1.2.0
	 */
	public function cfish_dive_map_shortcode($atts)
	{
		ob_start();
		
		require_once( "$this->plugin_path/templates/divesites-map.php" );
		
		$content = ob_get_contents();
		ob_end_clean();
		return $content;		
	}
	

	public function dive_sites_cpt ()
	{
		$labels = array(
			'name' => _x('Dive Sites', 'Dive Site General Name', 'dive-sites-manager'),
			'singular_name' => _x('Dive Site', 'Dive Site Singular Name', 'dive-sites-manager'),
			'menu_name'             => __( 'Dive Sites', 'dive-sites-manager' ),
			'name_admin_bar'        => __( 'Dive Site', 'dive-sites-manager' ),
			'archives'              => __( 'Dive Site Archives', 'dive-sites-manager' ),
			'attributes'            => __( 'Dive Site Attributes', 'dive-sites-manager' ),
			'parent_item_colon'     => __( 'Parent Dive Site:', 'dive-sites-manager' ),
			'all_items'             => __( 'All Dive Sites', 'dive-sites-manager' ),
			'add_new_item'          => __( 'Add New Dive Site', 'dive-sites-manager' ),
			'add_new'               => __( 'Add New', 'dive-sites-manager' ),
			'new_item'              => __( 'New Dive Site', 'dive-sites-manager' ),
			'edit_item'             => __( 'Edit Dive Site', 'dive-sites-manager' ),
			'update_item'           => __( 'Update Dive Site', 'dive-sites-manager' ),
			'view_item'             => __( 'View Dive Site', 'dive-sites-manager' ),
			'view_items'            => __( 'View Dive Sites', 'dive-sites-manager' ),
			'search_items'          => __( 'Search Dive Site', 'dive-sites-manager' ),
			'not_found'             => __( 'Not found', 'dive-sites-manager' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'dive-sites-manager' ),
			'featured_image'        => __( 'Featured Image', 'dive-sites-manager' ),
			'set_featured_image'    => __( 'Set featured image', 'dive-sites-manager' ),
			'remove_featured_image' => __( 'Remove featured image', 'dive-sites-manager' ),
			'use_featured_image'    => __( 'Use as featured image', 'dive-sites-manager' ),
			'insert_into_item'      => __( 'Insert into item', 'dive-sites-manager' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'dive-sites-manager' ),
			'items_list'            => __( 'Dive Sites list', 'dive-sites-manager' ),
			'items_list_navigation' => __( 'Dive Sites list navigation', 'dive-sites-manager' ),
			'filter_items_list'     => __( 'Filter items list', 'dive-sites-manager' ),
		);

		$args = array(
			'label' => __( 'Dive Site', 'dive-sites-manager' ),
			'description' => __( 'Dive Site Description', 'dive-sites-manager' ),
			'labels' => $labels,
			'public' => true,
			'has_archive' => false,
			'menu_icon' => 'dashicons-palmtree',
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
			'show_in_rest' => true,
		);

		register_post_type ( 'divesite', $args );
	}

	public function add_meta_boxes()
	{
		add_meta_box(
			'dive_site_options',
			'Dive Site Options',
			array( $this, 'render_features_box' ),
			'divesite',
			'normal',
			'default'
		);
	}

	public function render_features_box($post)
	{
		wp_nonce_field( 'cfish_dive_site', 'cfish_dive_site_nonce' );

		$data = get_post_meta( $post->ID, '_cfish_dive_site_key', true );

		$options = get_option( 'cfish_dsm_characteristics');
		$characteristics = array();
		if (isset($options['total_characteristics'])) {
			for ($i=1; $i <= $options['total_characteristics'] ; $i++) {
				$characteristics[$i] = isset($data['characteristic_' . $i .'']) ? $data['characteristic_' . $i .''] : '';
				?>
				<p>
					<label class="meta-label" for="cfish_dive_site_characteristic_<?php echo $i;?>"><?php echo $options['characteristic_' . $i .''];?></label>
					<input type="text" id="cfish_dive_site_characteristic_<?php echo $i;?>" name="cfish_dive_site_characteristic_<?php echo $i;?>" class="widefat" value="<?php echo esc_attr( $characteristics[$i] ); ?>">
				</p>
				<?php
			}
		}
		/* MAP */
		if ( $this->activated( 'maps_manager' ) ) {

			$map_config =  get_option( 'cfish_dsm_map');

			$icon_text = '';
			if ($icon_url = wp_get_attachment_image_url( $map_config['icon'], 'map_icon')){

				$icon_text = 'iconUrl="'. $icon_url .'" iconSize="40,25"';
			}
			$drag = __('Drag Me', 'dive-sites-manager');

			$attributes ='';
		
			if ($map_config['map_tiles'] == 'watercolor'){
				$attributes .= ' tileurl=https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg subdomains=abcd     
				attribution="Leaflet; © OpenStreetMap; Map tiles by Stamen Design, under CC BY 3.0." ';
			}

			if (isset($data['loc-latitude']) && $data['loc-latitude'] != '' && $data['loc-longitude'] != '' && $data['zoom'] != ''){
				echo do_shortcode('[leaflet-map  zoom='. $data['zoom'] .' 
				lat='. $data['loc-latitude'] .' lng='. $data['loc-longitude'] .' zoomcontrol doubleClickZoom  scrollwheel 
				height=' . $map_config['height'].' ' . $attributes . ']');

			}
			else{
				echo do_shortcode('[leaflet-map  zoom=2 zoomcontrol doubleClickZoom  scrollwheel height='. $map_config['height']. '' . $attributes . ']');
			}

			 if ( isset($data['latitude']) && $data['latitude'] != '' &&  $data['longitude'] != ''){
				echo do_shortcode(sprintf('[leaflet-marker lat='. $data['latitude'] .' lng='. $data['longitude'] .' draggable 
				visible '.$icon_text.' ] %s [/leaflet-marker]',
				$drag));
			}
			else{
				echo do_shortcode(sprintf('[leaflet-marker draggable visible '.$icon_text.' ] %s [/leaflet-marker]',
				$drag));	
			} 
			?>
			<div class="wrap">
				<hr>
				<p class="description"><?php _e('Move the marker to get the latitude and longitude', 'dive-sites-manager'); ?></p>
				<p>
					<label class="h3" for="cfish-latitude"><?php _e('Latitude', 'dive-sites-manager'); ?></label> 
					<input type="text" name="cfish-latitude" id="cfish-latitude" readonly>
					<label class="h3" for="cfish-longitude"><?php _e('Longitude', 'dive-sites-manager'); ?></label>
					<input type="text" name="cfish-longitude" id="cfish-longitude" readonly>
					<input type="hidden" name="cfish-loc-zoom" id="cfish-loc-zoom"  readonly />
					<input type="hidden" name="cfish-loc-latitude" id="cfish-loc-latitude"  readonly />
					<input type="hidden" name="cfish-loc-longitude" id="cfish-loc-longitude"  readonly/>
				</p>
				
			<?php


		}	
	}

	public function save_meta_box($post_id)
	{
		if (! isset($_POST['cfish_dive_site_nonce'])) {
			return $post_id;
		}

		$nonce = $_POST['cfish_dive_site_nonce'];
		if (! wp_verify_nonce( $nonce, 'cfish_dive_site' )) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if (! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		$options = get_option( 'cfish_dsm_characteristics');
		$data = array();
		if (isset($options['total_characteristics'])) {
			for ($i=1; $i <= $options['total_characteristics'] ; $i++) {
				$data['characteristic_' . $i .''] = sanitize_text_field( $_POST['cfish_dive_site_characteristic_' . $i .''] );
			}
		}
		
		$data['latitude'] = sanitize_text_field( $_POST['cfish-latitude'] );
		$data['longitude'] = sanitize_text_field( $_POST['cfish-longitude'] );
		$data['zoom'] = sanitize_text_field( $_POST['cfish-loc-zoom'] );
		$data['loc-latitude'] = sanitize_text_field( $_POST['cfish-loc-latitude'] );
		$data['loc-longitude'] = sanitize_text_field( $_POST['cfish-loc-longitude'] );

		update_post_meta( $post_id, '_cfish_dive_site_key', $data );
	}

 	public function set_custom_columns($columns)
	{
		$title = $columns['title'];
		$author = $columns['author'];
		$date = $columns['date'];
		$location = $columns['location'];

		unset($columns['location'],$columns['title'], $columns['date'], $columns['author'] );

		$columns['location'] = $location;
		$columns['title'] = $title;
		$columns['author'] = $author;
		$columns['date'] = $date;
		
		return $columns;
	}

	public function set_custom_columns_data($column, $post_id)
	{
		//return $columns;
	}

	public function set_custom_columns_sortable($columns)
	{
		$columns['Location'] = __('Location', 'dive-sites-manager');
		$columns['author'] = __('Author', 'dive-sites-manager');

		return $columns;
	} 
}