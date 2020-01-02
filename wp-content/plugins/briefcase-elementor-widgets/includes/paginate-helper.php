<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper functions
 *
 * @package   New Helpers for features 
 */
if ( ! class_exists( 'Bew_Paginate' ) ) {

	class Bew_Paginate {

		public function __construct() {
			add_filter( 'loop_shop_per_page', array( $this, 'loop_shop_per_page' ), 100 );

		}

	public static function loop_shop_per_page() {
		
		global $bewpaginate;
		
		$cols = $bewpaginate;
		return $cols;
	}

		
	}

	new Bew_Paginate();
}
