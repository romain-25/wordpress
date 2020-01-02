var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew always visible mode
	bewAVMT();
} );

$j( document ).ajaxComplete( function() {
	"use strict";
	// Bew always visible mode
	bewAVMT();
} );

// Make sure you run this code under Elementor..
$j( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew_dynamic.default', function() {
		"use strict";
	// Bew always visible mode
	bewAVMT();
	});
	} );
	

/* ==============================================
WOOCOMMERCE ALWAYS VISIBLE MODE TOP
============================================== */	


function bewAVMT() {
	
	// Add header class to body after pass add to cart buttom
	
	if ($j('#bew-cart').length) {
	var showmeTop = $j('#bew-cart').offset().top;
	}
	
	$j(window).bind( 'load scroll', function() {

		if ( $j(window).scrollTop() > showmeTop + 66 ) {

			$j( '#top-avm' ).addClass( 'show' );

		} else {

			$j( '#top-avm' ).removeClass( 'show' );

		}

	});

};