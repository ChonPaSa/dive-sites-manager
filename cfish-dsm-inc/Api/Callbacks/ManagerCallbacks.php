<?php 
/**
 * @package  DiveSitesManager
 * @since	1.0.0
 */
namespace cfishDSMInc\Api\Callbacks;

use cfishDSMInc\Base\BaseController;

class ManagerCallbacks extends BaseController
{
	public function adminSectionManager()
	{
		_e('Choose the features that you want to (de)activate', 'dive-sites-manager');
	}

/**
 * Methods to sanitize and validate the fields inside options
 */

	public function validate_checkbox( $checked ) 
	{
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}

	public function validate_number( $number ) 
	{
		$number = absint( $number );
	    return ( $number ? $number : 1 );
	}

	public function validate_text( $text ) 
	{
		$text = sanitize_text_field( $text );

		

			if(is_null( $text ) ) {
				add_settings_error(
					'dive_sites_characteristics_settings',
					esc_attr( 'settings_updated' ), //becomes part of id attribute of error message
					__( 'It can not be empty', 'dive-sites-manager' ), //default text zone
					'error'
				);
				die;
				//$input = get_option( 'my_option' ); //keep old value
			}
		
	
		return  ( ! is_null( $text ) ? $text : 'ERROR');
	}

	public function validate_color( $color )
    {
		$color = sanitize_hex_color( $color );
		return ( ! is_null( $color ) ? $color : '#282828');
	}
	
	public function validate_icon( $icon ) 
	{
		$icon = sanitize_text_field( $icon );
		return (strpos($icon, 'dashicons-') === 0 ? $icon : 'dashicons-smiley');
	}

	public function validate_select( $input ) 
	{
		//TODO. Now used
		return  $value ;
	}

	public function validate_radio( $input ) 
	{
		$output = sanitize_key($input);
		return  $output ;
	}
	
/**
 * Methods to display the fields
 */

	public function checkboxField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$checkbox = get_option( $option_name );
		$checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;
		echo '<div class="' . $classes . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="1" class="" ' . ( $checked ? 'checked' : '') . '><label for="' . $name . '"><div></div></label></div>';
		echo '<p>' . $args['description'] . '</p>';
	}

	public function inputField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$input = get_option( $option_name );
		$inputValue = isset($input[$name]) ? $input[$name] : '';
		echo '<div class="' . $classes . '"><input type="text" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="'.$inputValue.'" class="" required><label for="' . $name . '"><div></div></label></div>';
		echo '<p>' . $args['description'] . '</p>';
	}

	public function numberField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$value = $args['default'];
		$max = $args['max'];
		$option_name = $args['option_name'];
		$input = get_option( $option_name );
		$inputValue = isset($input[$name]) ? $input[$name] : $value;
		echo '<div class="' . $classes . '"><input type="number" min="1" max="'.$max.'" id="' . $name . '" name="' . $option_name . '[' . $name . ']" value="'.$inputValue.'" class=""><label for="' . $name . '"><div></div></label></div>';
		echo '<p>' . $args['description'] . '</p>';
	}

	public function colorField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$input = get_option( $option_name );
		$inputValue = isset($input[$name]) ? $input[$name] : '#282828';
		echo '<div class="' . $classes . '"><input id="' . $name . '" name="' . $option_name . '[' . $name . ']"  value="' . $inputValue . '" type="text"  class="color-picker" /></div>';
		echo '<p>' . $args['description'] . '</p>';
	}

	public function selectField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$values = $args['values'];
		$option_name = $args['option_name'];
    	echo '<label for="' . $name . '"></label>';
		echo '<select name="' . $name . '" id="' . $name . '">';
		foreach( $values as $value => $label ){
			echo '<option value="'.$value. '">' . $label. '</option>';
		}
		echo '</select>';
		echo '<p>' . $args['description'] . '</p>';
	}

	public function radioField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$values = $args['values'];
		$option_name = $args['option_name'];
		$input = get_option( $option_name );
		$inputValue =  $input[$name] ;
		echo '<label for="' . $name . '"></label>';
		echo '<p>' . $args['description'] . '</p>';		
		foreach( $values as $value => $label ){
			echo '<input type="radio" name="' . $option_name . '[' . $name . ']"   value="' . $value .'" ';
			echo ($value == $inputValue)? ' checked ' : '';
			echo '>';
			echo '<label for="' . $value .' ">' . $label . '</label><br>';
		}
		echo '</select>';

	}

	public function iconField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$input = get_option( $option_name );
		$inputValue = isset($input[$name]) ? $input[$name] : __('Choose Icon', 'dive-sites-manager');
		echo '<div class="' . $classes . '">';
		echo '<input class="regular-text" type="text" id="dashicons_picker_' . $name . '" name="' . $option_name . '[' . $name . ']" value="' . $inputValue. '"/>';
		echo '<input type="button" data-target="#dashicons_picker_' . $name . '" class="button dashicons-picker" value="'. __('Choose Icon', 'dive-sites-manager') .'" />';
		echo '</div>';
		echo '<p>' . $args['description'] . '</p>';	
	}

	public function imageField( $args )
	{
		$name = $args['label_for'];
		$classes = $args['class'];
		$option_name = $args['option_name'];
		$input = get_option( $option_name );
		$image_id = $input[$name] ;

		if( $image = wp_get_attachment_image_src( $image_id , 'map_icon' ) ) {
 
			echo '<a href="#" class="js-image-upload"><img src="' . $image[0] . '" /></a>
				  <a href="#" class="js-image-delete">Remove image</a>
				  <input type="hidden" name="' . $option_name . '[' . $name . ']" value="' . $image_id . '">';
		}
		else {
		 
			echo '<a href="#" class="js-image-upload">Upload image</a>
				  <a href="#" class="js-image-delete" style="display:none">Remove image</a>
				  <input type="hidden" name="' . $option_name . '[' . $name . ']"  value="">';
		 
		}
		echo '<p>' . $args['description'] . '</p>';
	}



}