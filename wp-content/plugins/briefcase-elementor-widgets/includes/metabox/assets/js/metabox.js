( function( $ ) {
	"use strict";

	$( document ).on( 'ready', function() {
	
		// Show/hide apply on Woocommerce Shop and Categories 
		var templateTypeField   = $( '#butterbean-control-briefcase_template_layout select' ),
			templateTypedVal  	= templateTypeField.val(),
			wooShopSetting 	= $( '#butterbean-control-briefcase_template_layout_shop' ),
			wooCatSetting 	= $( '#butterbean-control-briefcase_template_layout_cat' );
			
		wooShopSetting.hide();
		wooCatSetting.hide();

		if ( templateTypedVal === 'woo-shop' ) {
			wooShopSetting.show();
		}
		
		if ( templateTypedVal === 'woo-cat' ) {
			wooCatSetting.show();
		}
	
	templateTypeField.change( function () {

			wooShopSetting.hide();
			wooCatSetting.hide();

			if ( $( this ).val() == 'woo-shop' ) {
				wooShopSetting.show();
			}
			
			if ( $( this ).val() == 'woo-cat' ) {
				wooCatSetting.show();
			}

		} );
		
	} );
	
} ) ( jQuery );