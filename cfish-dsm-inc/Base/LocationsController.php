<?php 
/**
 * @package  DiveSitesManager
 * @since	1.1.0
 */
namespace cfishDSMInc\Base;

use cfishDSMInc\Api\SettingsApi;
use cfishDSMInc\Base\BaseController;


class LocationsController extends BaseController
{
	public function register()
	{
		if ( ! $this->activated( 'locations_manager' ) ) return;

		add_action( 'init', array( $this, 'registerCustomTaxonomy' ));

		if ( $this->activated( 'maps_manager' ) ) {

			add_action( 'init', array( $this, 'register_term_meta' ));
			add_action( 'location_add_form_fields', array( $this, 'add_form_field_term_meta' ));
			add_action( 'location_edit_form_fields', array( $this, 'edit_form_field_term_meta' ));
			add_action( 'edit_location', array( $this,   'save_term_meta' ));
			add_action( 'create_location', array( $this, 'save_term_meta' ));

		}
	}


	public function registerCustomTaxonomy()
	{
			$labels = array(
				'name'              => __('Location','dive-sites-manager') ,
				'singular_name'     => __('Location', 'dive-sites-manager') ,
				'menu_name'         => __('Locations', 'dive-sites-manager') , 
 				'search_items'      => __('Search Location', 'dive-sites-manager') ,
				'all_items'         => __('All Locations', 'dive-sites-manager') ,
				'parent_item'       => __('Parent Location', 'dive-sites-manager') ,
				'parent_item_colon' => __('Parent Location:', 'dive-sites-manager') ,
				'edit_item'         => __('Edit Location', 'dive-sites-manager') ,
				'update_item'       => __('Update Location', 'dive-sites-manager') ,
				'add_new_item'      => __('Add New Location', 'dive-sites-manager') ,
				'new_item_name'     => __('New Location', 'dive-sites-manager') ,
			);

			$taxonomy = array(
				'hierarchical'      => true ,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_rest' 		=> true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'location' ),
				'objects'           => array('divesite')
			);

			$objects = isset($taxonomy['objects']) ? $taxonomy['objects'] : null;
			register_taxonomy( $taxonomy['rewrite']['slug'], $objects, $taxonomy);
	}


	public function register_term_meta() 
	{
		register_meta( 'term', 'cfish-loc-zoom', array( $this, 'sanitize_term_meta' ));
		register_meta( 'term', 'cfish-loc-latitude', array( $this, 'sanitize_term_meta' ));
		register_meta( 'term', 'cfish-loc-longitude', array( $this, 'sanitize_term_meta' ));
	}


	public function sanitize_term_meta( $value ) 
	{
		return sanitize_text_field ($value);
	}

	
	public function get_term_meta( $term_id ) 
	{
		$value['zoom'] = $this->sanitize_term_meta( get_term_meta( $term_id, 'cfish-loc-zoom', true ) );
		$value['latitude'] = $this->sanitize_term_meta( get_term_meta( $term_id, 'cfish-loc-latitude', true ) );
		$value['longitude'] = $this->sanitize_term_meta( get_term_meta( $term_id, 'cfish-loc-longitude', true ) );
		return $value;
	}


	public function add_form_field_term_meta() 
	{ 
		$map_config =  get_option( 'cfish_dsm_map');

		$attributes ='';
	
		if ($map_config['map_tiles'] == 'watercolor'){
			$attributes .= ' tileurl=https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg subdomains=abcd     
			attribution="Leaflet; © OpenStreetMap; Map tiles by Stamen Design, under CC BY 3.0." ';
		}
		?>
		<h2><?php _e('Location Overview Map', 'dive-sites-manager'); ?></h2>
		<p class="description"><?php _e('Move the map and zoom to get the map for this location:', 'dive-sites-manager'); ?></p>
		<?php echo do_shortcode('[leaflet-map zoom=1 lat=0.2619 lng=-1.1373 zoomcontrol doubleClickZoom height=' . $map_config['height'].' 0 
		scrollwheel ' . $attributes . ']');
			  echo do_shortcode('[leaflet-marker iconUrl="a"]');
		?>
		<div class="form-field">
			<label for="cfish-loc-zoom"><?php _e( 'Zoom', 'dive-sites-manager' ); ?></label>
			<input type="text" name="cfish-loc-zoom" id="cfish-loc-zoom" value="" readonly />
		</div>
		<div class="form-field">
			<label for="cfish-loc-latitude"><?php _e( 'Latitude', 'dive-sites-manager' ); ?></label>
			<input type="text" name="cfish-loc-latitude" id="cfish-loc-latitude" value="" readonly />
		</div>
		<div class="form-field">
			<label for="cfish-loc-longitude"><?php _e( 'Longitude', 'dive-sites-manager' ); ?></label>
			<input type="text" name="cfish-loc-longitude" id="cfish-loc-longitude" value="" readonly />
		</div>
		<?php 
	}


	public function edit_form_field_term_meta( $term )
	 {
		$value  = $this->get_term_meta( $term->term_id );
		
		$map_config =  get_option( 'cfish_dsm_map');
		
		$attributes ='';

		$attributes .= ' zoom='. ($value['zoom']? $value['zoom']: '2');
		$attributes .= ' lat='. ($value['latitude']? $value['latitude']: '14.257298335563107');
		$attributes .= ' lng='. ($value['longitude']? $value['longitude']: '21.796875000000007');
 
	
		if ($map_config['map_tiles'] == 'watercolor'){
			$attributes .= ' tileurl=https://stamen-tiles-{s}.a.ssl.fastly.net/watercolor/{z}/{x}/{y}.jpg subdomains=abcd     
			attribution="Leaflet; © OpenStreetMap; Map tiles by Stamen Design, under CC BY 3.0." ';
		}
		?>
		<tr class="form-field ">
			<td colspan="2">
				<h2><?php _e('Location Overview Map', 'dive-sites-manager'); ?></h2>
				<p class="description"><?php _e('Move the map and zoom to get the map for this location:', 'dive-sites-manager'); ?></p>
				<?php echo do_shortcode('[leaflet-map zoomcontrol doubleClickZoom height=' . 
				$map_config['height'].' scrollwheel ' . $attributes . ']');
					echo do_shortcode('[leaflet-marker iconUrl="a"]');
				?>
			</td>
		</tr>
		<tr class="form-field ">
			<th scope="row"><label for="cfish-loc-zoom"><?php _e( 'Zoom', 'dive-sites-manager' ); ?></label></th>
			<td>
				<input type="text" name="cfish-loc-zoom" id="cfish-loc-zoom" value="<?php echo esc_attr( $value['zoom'] ); ?>" readonly />
			</td>
		</tr>
		<tr class="form-field ">
			<th scope="row"><label for="cfish-loc-latitude"><?php _e( 'Latitude', 'dive-sites-manager' ); ?></label></th>
			<td>
				<input type="text" name="cfish-loc-latitude" id="cfish-loc-latitude" value="<?php echo esc_attr( $value['latitude'] ); ?>" readonly />
			</td>
		</tr>
		<tr class="form-field ">
			<th scope="row"><label for="cfish-loc-longitude"><?php _e( 'Longitude', 'dive-sites-manager' ); ?></label></th>
			<td>
				<input type="text" name="cfish-loc-longitude" id="cfish-loc-longitude" value="<?php echo esc_attr( $value['longitude'] ); ?>" readonly/>
			</td>
		</tr>
		<?php 
	}


	public function save_term_meta( $term_id ) 
	{
		$old  = $this->get_term_meta( $term_id );
		$new['zoom'] = isset( $_POST['cfish-loc-zoom'] ) ? $this->sanitize_term_meta ( $_POST['cfish-loc-zoom'] ) : '';
		$new['latitude'] = isset( $_POST['cfish-loc-latitude'] ) ? $this->sanitize_term_meta ( $_POST['cfish-loc-latitude'] ) : '';
		$new['longitude'] = isset( $_POST['cfish-loc-longitude'] ) ? $this->sanitize_term_meta ( $_POST['cfish-loc-longitude'] ) : '';

		if ( $old['zoom'] && '' === $new['zoom'] )
			delete_term_meta( $term_id, 'cfish-loc-zoom' );
		else if ( $old['zoom'] !== $new['zoom'] )
			update_term_meta( $term_id, 'cfish-loc-zoom', $new['zoom'] );

		if ( $old['latitude'] && '' === $new['latitude'] )
			delete_term_meta( $term_id, 'cfish-loc-latitude' );
		else if ( $old['latitude'] !== $new['latitude'] )
			update_term_meta( $term_id, 'cfish-loc-latitude', $new['latitude'] );

		if ( $old['longitude'] && '' === $new['longitude'] )
			delete_term_meta( $term_id, 'cfish-loc-longitude' );
		else if ( $old['longitude'] !== $new['longitude'] )
			update_term_meta( $term_id, 'cfish-loc-longitude', $new['longitude'] );


	}



}