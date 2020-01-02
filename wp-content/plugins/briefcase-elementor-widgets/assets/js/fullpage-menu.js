var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew always visible mode
	fullpagemenu();
} );

$j( document ).ajaxComplete( function() {
	"use strict";
	// Bew always visible mode
	fullpagemenu();
} );

// Make sure you run this code under Elementor..
$j( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew-fullpage.default', function() {
		"use strict";
	// Bew always visible mode
	fullpagemenu();
	});
	} );
	

/* ==============================================
WOOCOMMERCE ALWAYS VISIBLE MODE TOP
============================================== */	


function fullpagemenu() {
	
		// Hide Menu fullpage.	
				
				$j(window).scroll(function () {
				
  
				$j('#menu-fullpage').addClass('hide-logo');    
				
				
				});			

};