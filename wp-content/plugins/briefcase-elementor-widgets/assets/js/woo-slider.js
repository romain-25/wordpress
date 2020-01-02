var $j = jQuery.noConflict();
		
		// Make sure you run this code under Elementor..
		$j( window ).on( 'elementor/frontend/init', function() {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/bew-woo-grid.default', function() {
				"use strict";
			// Slider
			if ($j( 'body' ).hasClass("elementor-editor-active")) {			
			bewInitCarousel();
			}
			});
		} );
		
		// Make sure you run this code under Elementor..
		$j( window ).on( 'elementor/frontend/init', function() {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/bew_dynamic.default', function() {
				"use strict";
			// Slider
			if ($j( 'body' ).hasClass("elementor-editor-active")) {			
			bewInitCarousel2();
			}
			});
		} );
	
		
		$j( document ).on( 'ready', function() {
			"use strict";
			// Slider
			if (!$j( 'body' ).hasClass("elementor-editor-active")) {			
			bewInitCarousel();
			}
		} );
	

/* ==============================================
Slider
============================================== */
function bewInitCarousel( $context ) {
	"use strict"

	var $carousel = $j( '.bew-product-entry-slider', $context ),
		$thumbnailscarousel = $j( '.bew-thumbnails-slider', $context ),
		$shopgrid = $j( '.bew-woo-grid', $context ),
		$bewimage = $j( '.bew-product-image'),
		windowWidth   = $j( window ).width();

	// If is active slider style
	if ( $carousel.hasClass( 'woo-entry-image' )) {
		$j('body').addClass( 'bew-slider-active' );
	}

	if ( $j( '.bew-slider-image').hasClass( 'thumbnails-vertical' ) ) {
		$j('body').addClass( 'bew-slider-thumbnails-vertical' );
	}
	
	if ( !$j('body').hasClass( 'bew-slider-active' ) || $j('.bew-woo-grid-slider .products').hasClass( 'bew-products-slider' ) ) {
		return;
	}
	

	// If RTL
	if ( $j( 'body' ).hasClass( 'rtl' ) ) {
		var rtl = true;
	} else {
		var rtl = false;
	}

	// Return autoplay to false if woo slider
	if ( $carousel.hasClass( 'woo-entry-image' ) ) {
		var autoplay = false;
	} else {
		var autoplay = true;
	}

	// Slide speed
	var speed = 7000;
	
	if ( $j('body').hasClass( 'single-product' ) ) {
		
		// Gallery slider
		$carousel.imagesLoaded( function() {
			$carousel.not('.slick-initialized').slick( {
				autoplay: autoplay,
				autoplaySpeed: speed,
				prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-angle-left"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="fa fa-angle-right"></span></button>',
				rtl: rtl,
			} );
		} );
		
	}else{
		
		if ( $j('body').hasClass( 'bew-slider-thumbnails-vertical' ) && windowWidth >= 1025 && !$j( 'body' ).hasClass("elementor-editor-active") ) {
			// Generate width for slider		
			$j(function() {
					
				var $wrapper = $j('.bew-products');//the element we want to measure
				var wrapperWidth = $wrapper.width();//get its width 		
				var data_settings = $j(".elementor-widget-bew-woo-grid").data('settings');
				var data_columns = $j(".bew-woo-grid .products").data('columns');
					
					if ( typeof data_settings != 'undefined') {	
						if ( windowWidth <= 544 ) {					
							var data_settings_columns = data_settings["columns-mobile"];					
							
						} else if ( windowWidth >= 545 && windowWidth <= 767 ) {					
							var data_settings_columns = data_settings["columns-mobile"];
						
						} else if ( windowWidth >= 768 && windowWidth <= 991 ) {					
							var data_settings_columns = data_settings["columns-tablet"];
							
						} else if ( windowWidth >= 992 && windowWidth <= 1024 ) {					
							var data_settings_columns = data_settings["columns-tablet"];
							
						} else if ( windowWidth >= 1025 ) {					
							var data_settings_columns = data_settings["columns"];
						}
					}
				
				
					if ( data_settings_columns != null  ) {				
						if (data_columns == null ){
							var columns_settings = data_settings_columns;
						}else{
							var columns_settings = data_columns;
						}
						
						var column_gap_settings = data_settings["column_gap"];
						var gap_size = column_gap_settings["size"]*(columns_settings - 1);							
						var wrapperWidth = (wrapperWidth - gap_size)/ columns_settings;
						
						$j(".bew-products .elementor-container").css("max-width", wrapperWidth);
				
					}
			
			
			// Change width on resize window
					
				var windowResize={
				width:0,
				init:function() {
					this.width=$j(window).width();
				},
				checkResize:function(callback) {
					if( this.width!=$j(window).width() ) {
						callback.apply();
					}
				}
				};
				windowResize.init();
				$j(window).resize(function() {windowResize.checkResize(function() {

						wrapperWidth = $wrapper.width();//re-get the width
						
						console.log($j(window).width() );	
						console.log($j(window).innerWidth() );
						console.log($j(window).outerWidth() );

						function viewport() {
							var e = window, a = 'inner';
							if (!('innerWidth' in window )) {
								a = 'client';
								e = document.documentElement || document.body;
							}
							return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
						}
												
						if (viewport().width  >= 1024 ) {	
							console.log(wrapperWidth);	
							
							var data_settings = $j(".elementor-widget-bew-woo-grid").data('settings');
							
							if ( typeof data_settings != 'undefined' ) {			
								var columns_settings = data_settings["columns"];
								var column_gap_settings = data_settings["column_gap"];
								var gap_size = column_gap_settings["size"]*(columns_settings - 1);
							}
									
							wrapperWidth = (wrapperWidth - gap_size)/ columns_settings;

							console.log(wrapperWidth);	
							
							$j(".bew-products .elementor-container").css("max-width", wrapperWidth);			
							$carousel.slick('setPosition');
						
						}else{
							$j(".bew-products .elementor-container").css("max-width", '');
						}
					});
				});		

			});
		
		}

		
		if ( $j('.bew-product-image-type-slider-image').hasClass( 'bew-slider-thumbnail-yes' )) {
			// Create slick slider 
			
			$carousel.each(function(key, item) {
				
				var $form = $j( 'form.variations_form' );
				var sliderIdName = 'slider' + key;
				var sliderNavIdName = 'sliderNav' + key;

				this.id = sliderIdName;
				  $j('.bew-thumbnails-slider')[key].id = sliderNavIdName;

				var sliderId = '#' + sliderIdName;
				var sliderNavId = '#' + sliderNavIdName;
				  
				  $j( sliderId + " img").not($j(".bew-current img")).removeClass('wp-post-image');	
				  $j( sliderNavId + " img").removeClass('wp-post-image');

				
				var s1 = JSON.parse($j( '.bew-thumbnails-slider' ).attr( "data-carousel" ) );		
				var s2 = { asNavFor: sliderId };
					
				var settings = JSON.parse(JSON.stringify($j.extend(false,{},s1,s2)));


				// Gallery slider
				$j(sliderId).imagesLoaded( function() {
					$j(sliderId).slick( {
						autoplay: autoplay,
						autoplaySpeed: speed,
						variableWidth: false,				
						prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-angle-left"></span></button>',
						nextArrow: '<button type="button" class="slick-next"><span class="fa fa-angle-right"></span></button>',
						rtl: rtl,
						asNavFor: sliderNavId
					} );
					
					$j(sliderNavId).slick(settings);
						
				} );
				
				
			
			} );
			
			 // slick go to index 0 slide	
			$j( '.variations_form' ).on( 'click', function() { 

				var product_id  = $j(this).data("product_id");
						
				$j("#bew-image-" + product_id + " .bew-product-entry-slider").slick('slickGoTo', 0, true); 
			
			} );
		
		} else {
			
			// Slider with no thumbnails
			$carousel.imagesLoaded( function() {
				$carousel.not('.slick-initialized').slick( {
					autoplay: autoplay,
					autoplaySpeed: speed,
					prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-angle-left"></span></button>',
					nextArrow: '<button type="button" class="slick-next"><span class="fa fa-angle-right"></span></button>',
					rtl: rtl,
				} );
			} );
			
		}
		
		if ( $j('body').hasClass( 'bew-slider-thumbnails-vertical' ) ) {
			// Change width with col-switcher
			$j(function() {
				var $colSwitcherslide = $j( '.col-switcher' );
				var $wrapper = $j('.bew-products .products');//the element we want to measure
				var wrapperWidth = $wrapper.width();//get its width
				var wrapperWidth2 = '';//get its width 
				
				// Change columns when click
				$colSwitcherslide.find( 'a' ).on( 'click', function() {
					
					var data_columns = $j(".bew-woo-grid .products").data('columns');				
					var data_settings = $j(".elementor-widget-bew-woo-grid").data('settings');
					
					if ( typeof data_settings != 'undefined' ) {			
						var column_gap_settings = data_settings["column_gap"];
						var gap_size = column_gap_settings["size"]*(data_columns - 1);
					}
							
					wrapperWidth2 = (wrapperWidth - gap_size)/ data_columns;			
					
					$j(".bew-products .elementor-container").css("max-width", wrapperWidth2);			
					$carousel.slick('setPosition');
					$thumbnailscarousel.slick('setPosition');			
				} );
			});
		} else {
						
			var $colSwitcherslide = $j( '.col-switcher' );
		
			// Change columns when click
			$colSwitcherslide.find( 'a' ).on( 'click', function() {
				
			$carousel.slick('setPosition');
			$thumbnailscarousel.slick('setPosition');
			
			} );
			
		}
	}

	// Change columns when click on elementor editor	
	if (jQuery( 'body' ).hasClass("elementor-editor-active")) {
		var $div = $j(".elementor-widget-bew-woo-grid");
		var observer = new MutationObserver(function(mutations) {
		  mutations.forEach(function(mutation) {
			if (mutation.attributeName === "class") {
			  var attributeValue = $j(mutation.target).prop(mutation.attributeName);

			  
			  $carousel.slick('setPosition');
			  $thumbnailscarousel.slick('setPosition');
			}
		  });
		});
		observer.observe($div[0], {
		  attributes: true
		});
	}
}

function bewInitCarousel2( $context ) {
	"use strict"

	var $carousel = $j( '.bew-product-entry-slider', $context ),
		$bewimage = $j( '.bew-product-image');

	// If is active slider style
	if ( $carousel.hasClass( 'woo-entry-image' ) ) {
		$j('body').addClass( 'bew-slider-active' );
	}
	
	if ( $j( '.bew-slider-image').hasClass( 'thumbnails-vertical' ) ) {
		$j('body').addClass( 'bew-slider-thumbnails-vertical' );
	} 

	if ( !$j('body').hasClass( 'bew-slider-active' ) || $j('.bew-woo-grid-slider .products').hasClass( 'bew-products-slider'))  {
		return;
	}

	// If RTL
	if ( $j( 'body' ).hasClass( 'rtl' ) ) {
		var rtl = true;
	} else {
		var rtl = false;
	}

	// Return autoplay to false if woo slider
	if ( $carousel.hasClass( 'woo-entry-image' ) ) {
		var autoplay = false;
	} else {
		var autoplay = true;
	}

	// Slide speed
	var speed = 7000;
	
	// Thumbnails
	var $thumbnailscarousel = $j( '.bew-thumbnails-slider');
	var s1 = JSON.parse($j( '.bew-thumbnails-slider' ).attr( "data-carousel" ) );		
	var s2 = { asNavFor: '.bew-product-entry-slider' };
				
	var settings = JSON.parse(JSON.stringify($j.extend(false,{},s1,s2)));

	// Gallery slider
	$carousel.imagesLoaded( function() {
		$carousel.not('.slick-initialized').slick( {
			autoplay: autoplay,
			autoplaySpeed: speed,
			prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-angle-left"></span></button>',
			nextArrow: '<button type="button" class="slick-next"><span class="fa fa-angle-right"></span></button>',
			rtl: rtl,
			asNavFor: '.bew-thumbnails-slider'
		} );
		
		$thumbnailscarousel.not('.slick-initialized').slick(settings);
	} );
	
}