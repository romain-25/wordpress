var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew product Add to cart
	bewaddtocart();
} );

$j( document ).ajaxComplete( function() {
	"use strict";
	// Bew product Add to cart
	bewaddtocart();
} );

// Make sure you run this code under Elementor..
$j( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew_dynamic.default', function() {
		"use strict";
	// Bew product Add to cart
	bewaddtocart();
	});
	} );
	

/* ==============================================
WOOCOMMERCE PRODUCT ADD TO CART
============================================== */	


function bewaddtocart() {
	
	
	// product Add to cart hidde icon
	
		var buttom_selectors = $j('#bew-cart a');
		var preview = $j('.elementor-editor-active').length;
				
		if ( preview  == 0){
		buttom_selectors.on('click', function(){
			buttom_selectors.removeClass('hidde');
			$j(this).addClass('hidde');
			
		});
		}
	// product Add to cart	overlay image
	
			$j('.bew-product-image').hover(function(e) {
			
			if(this.id){
			$j('.' + this.id)[e.type == 'mouseenter' ? 'addClass' : 'removeClass']('show-add-to-cart'); 	
			}else {
			$j('.' + $j(this).attr('class').split(' ')[2])[e.type == 'mouseenter' ? 'addClass' : 'removeClass']('show-add-to-cart'); 	
			}
			
			});	
	
};