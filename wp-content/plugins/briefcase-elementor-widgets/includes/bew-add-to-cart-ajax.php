<?php
namespace Briefcase;


class bewAddtocartAjax{
	private static $_instance = null;
   

public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
}
	
public function __construct() {
		// Ajax single product add to cart
		add_action( 'wp_ajax_bew_add_cart_single_product', array( $this, 'add_cart_single_product_ajax' ) );
		add_action( 'wp_ajax_nopriv_bew_add_cart_single_product', array( $this, 'add_cart_single_product_ajax' ) );	
}

/**
 * Single Product add to cart ajax request.
 *
 * @since 1.4.0
 */
public static function add_cart_single_product_ajax() {

		$product_id   	= sanitize_text_field( $_POST['product_id'] );
		$variation_id 	= sanitize_text_field( $_POST['variation_id'] );
		$variation 		= $_POST['variation'];
		$quantity     	= sanitize_text_field( $_POST['quantity'] );
		
		if ( $variation_id ) {
			WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
		} else {
			WC()->cart->add_to_cart( $product_id, $quantity );
		}
		die();

	}

}
bewAddtocartAjax::instance();