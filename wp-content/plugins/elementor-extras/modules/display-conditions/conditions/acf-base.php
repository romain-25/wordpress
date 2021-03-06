<?php
namespace ElementorExtras\Modules\DisplayConditions\Conditions;

// Elementor Extras Classes
use ElementorExtras\Base\Condition;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * \Modules\DisplayConditions\Conditions\Acf_Base
 *
 * @since  2.2.0
 */
class Acf_Base extends Condition {

	public $name_control_defaults = [
		'type' 			=> 'ee-query',
		'post_type' 	=> '',
		'options' 		=> [],
		'query_type' 	=> 'acf',
		'label_block' 	=> true,
		'multiple' 		=> false,
		'query_options'	=> [
			'show_type' => false,
			'show_field_type' => true,
		],
	];

	/**
	 * Checks if current condition is supported
	 *
	 * @since  2.2.0
	 * @return bool
	 */
	public static function is_supported() {
		return class_exists( '\acf' );
	}

	/**
	 * Get Group
	 * 
	 * Get the group of the condition
	 *
	 * @since  2.2.0
	 * @return string
	 */
	public function get_group() {
		return 'acf';
	}

	/**
	 * Get Field Post
	 * 
	 * Retrieve the ACF field post object by id
	 *
	 * @since  2.2.0
	 * @return string
	 */
	public function get_field_post( $post_id ) {
		global $post;

		$field_post = get_posts( [
			'post__in'		=> [ $post_id ],
			'post_type'   	=> 'acf-field',
  			'post_status' 	=> 'publish',
			'numberposts' 	=> 1,
		] );

		if ( $field_post[0] ) {
			return $field_post;
		}

		return false;
	}

	/**
	 * Get Name Control Defaults
	 * 
	 * Get the settings for the name control
	 *
	 * @since  2.2.0
	 * @return array
	 */
	public function get_name_control_defaults() {
		return ;
	}
}
