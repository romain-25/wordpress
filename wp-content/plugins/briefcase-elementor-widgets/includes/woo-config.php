<?php

namespace Briefcase;


class Wooconfig{
	
	private static $_instance = null;
   

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {
		// Main Woo Filters
		
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'menu_cart_bew_fragments' ) );
			add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'mini_cart_bew_fragments' ) );
	}
	
	/**
		 * Add menu cart item to the Woo fragments so it updates with AJAX
		 *
		 * @since 1.1.0
		 */
	public function menu_cart_bew_fragments( $fragments ) {
						
			ob_start();
			$this->bew_woomenucart2();
			$fragments['span.woo-cart-quantity'] = ob_get_clean();

			return $fragments;
	}
	
	public function mini_cart_bew_fragments( $fragments ) {
			
			global $woocommerce;
			
			ob_start(); ?>
			<div class="shopping-cart-content">
			<?php woocommerce_mini_cart() ?>
			</div>
			<?php
			$fragments['div.shopping-cart-content'] = ob_get_clean();

			return $fragments;
	}
		
	public function bew_woomenucart2() {
			
			$count = WC()->cart->cart_contents_count;
			// Menu Cart WooCommerce
					if( class_exists( 'WooCommerce' ) ) { ?>
						<span class="woo-cart-quantity woo-cart-quantity-update "><?php echo $count ?></span>											
						<?php }
	}	
	 	
}
Wooconfig::instance();
