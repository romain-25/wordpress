<?php
namespace Briefcase;

defined( 'BEW_PATH' ) || exit;

class Galleryzoom {
	
	private static $_instance = null;
   

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {		
		
		add_action( 'wp', array( $this, 'remove_pgz_theme_support' ) );		
			
	}
		
	public function remove_pgz_theme_support() { 
	remove_theme_support( 'wc-product-gallery-zoom' );
	}
	 	
}
Galleryzoom::instance();
