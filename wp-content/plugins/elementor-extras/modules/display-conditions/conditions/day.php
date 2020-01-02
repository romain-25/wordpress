<?php
namespace ElementorExtras\Modules\DisplayConditions\Conditions;

// Elementor Extras Classes
use ElementorExtras\Base\Condition;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * \Modules\DisplayConditions\Conditions\Day
 *
 * @since  2.2.6
 */
class Day extends Condition {

	/**
	 * Get Group
	 * 
	 * Get the group of the condition
	 *
	 * @since  2.2.6
	 * @return string
	 */
	public function get_group() {
		return 'date_time';
	}

	/**
	 * Get Name
	 * 
	 * Get the name of the module
	 *
	 * @since  2.2.6
	 * @return string
	 */
	public function get_name() {
		return 'day';
	}

	/**
	 * Get Title
	 * 
	 * Get the title of the module
	 *
	 * @since  2.2.6
	 * @return string
	 */
	public function get_title() {
		return __( 'Day of Week', 'elementor-extras' );
	}

	/**
	 * Get Value Control
	 * 
	 * Get the settings for the value control
	 *
	 * @since  2.2.6
	 * @return string
	 */
	public function get_value_control() {
		return [
			'type' 			=> Controls_Manager::SELECT2,
			'multiple'		=> true,
			'options' => [
				'1' => __( 'Monday', 'elementor-extras' ),
				'2' => __( 'Tuesday', 'elementor-extras' ),
				'3' => __( 'Wednesday', 'elementor-extras' ),
				'4' => __( 'Thursday', 'elementor-extras' ),
				'5' => __( 'Friday', 'elementor-extras' ),
				'6' => __( 'Saturday', 'elementor-extras' ),
				'7' => __( 'Sunday', 'elementor-extras' ),
			],
			'label_block'	=> true,
			'default' 		=> '1',
		];
	}

	/**
	 * Check day of week
	 *
	 * Checks wether today falls inside a
	 * specified day of the week
	 *
	 * @since 2.2.6
	 *
	 * @access protected
	 *
	 * @param string  	$name  		The control name to check
	 * @param mixed  	$value  	The control value to check
	 * @param string  	$operator  	Comparison operator.
	 */
	public function check( $name, $operator, $value ) {

		$show = false;

		if ( is_array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $_key => $_value ) {
				if ( $_value === date( 'w' ) ) {
					$show = true; break;
				}
			}
		} else { $show = $value === date( 'w' ); }

		return self::compare( $show, true, $operator );
	}
}
