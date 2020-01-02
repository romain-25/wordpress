<?php
/**
 * Our BewElementorManager class.
 * This handles all our hooks and stuff.
 *
 * @package Bew-elementor
 */
defined( 'BEW_PATH' ) || exit;

/**
 * All the magic happens here.
 *
 * Class DtbakerElementorManager
 */
class BewDynamicField {

	/**
	 * Stores our instance that can (and is) accessed from various places.
	 *
	 * @var DtbakerElementorManager null
	 *
	 * @since 1.0.0
	 */
	private static $instance = null;

	/**
	 * Grab a static instance of this class.
	 *
	 * @since 1.0.0
	 *
	 * @return DtbakerElementorManager
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	// WooCommerce
	public function product_title(){
		global $product;
		if(!$product){
            return '';
        }
		return woocommerce_template_single_title(); 
	}	
	public function product_rating(){
		global $product;
		if(!$product){
            return '';
        }
		return woocommerce_template_single_rating();
	}
	public function product_price(){		
		global $product;
		if(!$product){
            return '';
        }			
		return woocommerce_template_single_price();
	}
	public function product_excerpt(){
		global $product;
		if(!$product){
            return '';
        }
		if ( is_product() ){
		return woocommerce_template_single_excerpt();
		} else {
		return  $product->get_short_description();		
		
		}
	}	
	public function product_meta(){
		global $product;
		if(!$product){
            return '';
        }
			
		return woocommerce_template_single_meta();
	}	
	public function product_sharing(){
		global $product;
		if(!$product){
            return '';
        }		
		return woocommerce_template_single_sharing();
	}
	public function product_add_to_cart(){
		
		global $product;
		if(!$product){
            return '';
        }
		global $post;
		
		$post_type = get_post_type($post->ID);	
					
		if( class_exists( 'WooCommerce' ) ) {
			
			switch ( $post_type ) {
			case 'product':		
			if ( is_product() ){
			return woocommerce_template_single_add_to_cart(); 		
			} 
			else {
			return woocommerce_template_loop_add_to_cart();	
			}		
			break;
			case 'elementor_library':	
			
			$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
			
				if ( 'woo-product' == $bew_template_type  ){				
				return woocommerce_template_single_add_to_cart(); 			
				}else {					
				return woocommerce_template_loop_add_to_cart();		
				}		    
			break;
			default:
			
			return woocommerce_template_loop_add_to_cart();	    
			}
		}
	}
		
	public function product_description(){
		global $product;
		if(!$product){
            return '';
        }
		
		return  $product->get_description();		
				
	}
	
	public function product_image(){
		global $product;
		if(!$product){
            return '';
        }
		$image = woocommerce_get_product_thumbnail( $size = 'full' );
		
		$image_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', 'div', 'class= bew-product-image', $image );
		
		return $image_html; 
	}
	
	public function product_gallery(){
		global $product;
		if(!$product){
            return '';
        }		
		return woocommerce_show_product_images();
	}
		
	public function product_tabs(){
		global $product;
			
		if(!$product){
            return '';
        }
			// Custom description callback
			add_filter( 'woocommerce_product_tabs', 'woo_custom_description_tab', 98 );
			function woo_custom_description_tab( $tabs ) {

				$tabs['description']['callback'] = 'woo_custom_description_tab_content';	// Custom description callback

				return $tabs;
			}

			function woo_custom_description_tab_content() {
				global $product, $post;
				$bewglobal = get_post_meta($post->ID, 'briefcase_apply_global', true);
				if (is_product() and $bewglobal == 'off' ) {
				
				echo 'este es custom descrition';
				} else {
					
				echo $product->get_description();
				}
			}
			
		
		return woocommerce_output_product_data_tabs();
	}
	
	public function product_comments(){
		global $product;
		
		if(!$product){
            return '';
        }
		
		return comments_template();
	}
	
	
	// EDD
	public function download_title(){
		return get_the_title();
	}
	
	
	
	// General
	public function page_id(){
		if(!empty($GLOBALS['briefcase_post_for_dynamic_fields']) && is_object($GLOBALS['briefcase_post_for_dynamic_fields']) && !empty($GLOBALS['briefcase_post_for_dynamic_fields']->ID)){
			return (int)$GLOBALS['briefcase_post_for_dynamic_fields']->ID;
		}else if(!empty($GLOBALS['briefcase_post_for_dynamic_fields']) && (int)$GLOBALS['briefcase_post_for_dynamic_fields']){
			return (int)$GLOBALS['briefcase_post_for_dynamic_fields'];
		}
		return 0;
	}
	public function post_title(){
		return get_the_title( !empty($GLOBALS['briefcase_post_for_dynamic_fields']) ? $GLOBALS['briefcase_post_for_dynamic_fields'] : null );
	}
	
	public function permalink(){
		return get_the_permalink( !empty($GLOBALS['briefcase_post_for_dynamic_fields']) ? $GLOBALS['briefcase_post_for_dynamic_fields'] : null );
	}
	public function post_thumbnail(){
		return get_the_post_thumbnail( !empty($GLOBALS['briefcase_post_for_dynamic_fields']) ? $GLOBALS['briefcase_post_for_dynamic_fields'] : null );
	}
	public function search_query(){
		return esc_html( !empty($_GET['s']) ? $_GET['s'] : '' );
	}


}

