var $j = jQuery.noConflict();

$j( document ).ready( function() {
	"use strict";
	// Bew product Add to cart
	bewgallery();
} );

	
/* ==============================================
WOOCOMMERCE PRODUCT IMAGE GALLERY
============================================== */	

function bewgallery() {


// Single Product Page
	
	var $window = $j( window ),
		$body   = $j( 'body' );


		if ( ! document.body.classList.contains( 'single-product' ) && document.querySelector( '.single-product' ) === null ) {
			return;
		}
		
		var _productGallery   = document.querySelector( '.woocommerce-product-gallery' ),
			_productGalleryDefault   = document.querySelector( '.bew-gallery-images' ),
			_mainImageSlider  = document.querySelector( '.woocommerce-product-gallery__slider' ),
			_thumbnailsSlider = document.querySelector( '.thumbnails-slider' );
			_product_gallery_layout = document.querySelector( '.bew-woo-gallery-view-sticky' ); 

		if ( _productGallery === null || _productGalleryDefault === null) {
			return;
		}
				
		var mainImageSlider = function() {
			
			if ( _product_gallery_layout != null) {				
				return;
			}	

			if ( $j( _mainImageSlider ).attr( 'data-carousel' ) == null ) {
				$j( _mainImageSlider ).css( {
					'opacity'   : 1,
					'visibility': 'visible'
				} );
				return;
			}

			if ( _productGallery.classList.contains( 'only-featured-image' ) ) {

				var _zoomTarget = _mainImageSlider.querySelector( '.woocommerce-product-gallery__image' );
				$( _zoomTarget ).trigger( 'zoom.destroy' );

				return;
			}

			var settings = JSON.parse( _mainImageSlider.getAttribute( 'data-carousel' ) );

			$j( _mainImageSlider ).slick( settings );
		};

		var mainImageZoom = function() {

			if ( _productGallery.classList.contains( 'only-featured-image' ) ) {
				//return;
			}

			if ( _productGallery.classList.contains( 'product-zoom-on' ) || _productGalleryDefault.classList.contains( 'product-zoom-on' ) ) {

				var _zoomTarget  = $j( '.woocommerce-product-gallery__image' ),
					_imageToZoom = _zoomTarget.find( 'img' );

				// But only zoom if the img is larger than its container.
				if ( _imageToZoom.attr( 'data-large_image_width' ) > _productGallery.offsetWidth ) {
					_zoomTarget.trigger( 'zoom.destroy' );
					_zoomTarget.zoom( {
						touch: false
					} );
				}
			}
		};

		var thumbnailsSlider = function() {

			if ( _product_gallery_layout != null ) {
				return;
			}
			
			if ( ! _thumbnailsSlider ) {
				return;
			}

			var settings = JSON.parse( _thumbnailsSlider.getAttribute( 'data-carousel' ) );

			settings.responsive = [{
				breakpoint: 768,
				settings  : {
					slidesToShow   : 4,
					vertical       : false,
					verticalSwiping: false,
					arrows         : false,
					dots           : true,
				},
			}];

			$j( _thumbnailsSlider ).slick( settings );			
			

			[].slice.call( _thumbnailsSlider.querySelectorAll( 'a.slick-slide' ) ).forEach( function( _slide ) {
				_slide.addEventListener( 'click', function( e ) {
					e.preventDefault();
				} );
			} );
		};

		var lightBoxHandler = function() {

			$j( _productGallery ).off( 'click', '.woocommerce-product-gallery__image a' );

			$j( _productGallery ).on( 'click', '.lightbox-btn, .woocommerce-product-gallery__image a', function( e ) {

				e.preventDefault();

				openPhotoSwipe( getImageIndex( e ) );
			} );
		}

		var variationHandler = function() {

			var $form = $j( 'form.variations_form' );
			
			if ( _product_gallery_layout != null ) {
				return;
			}
			
			$form.on( 'show_variation', function( e, variation ) {

				if ( variation && variation.image && variation.image.src && variation.image.src.length > 1 ) {

					var $slide            = $j( _mainImageSlider )
							.find( '.woocommerce-product-gallery__image[data-thumb="' + variation.image.thumb_src + '"]:not(.slick-cloned)' ),
						index             = $slide.index( '.woocommerce-product-gallery__image:not(.slick-cloned)' ),
						$product_img_wrap = $j( _productGallery )
							.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' )
							.eq( 0 ),
						$product_img      = $j( _productGallery.querySelector( '.wp-post-image' ) ),
						$product_link     = $product_img_wrap.find( 'a' ).eq( 0 );
											
						
						// set image to variation
						if ( $j( _mainImageSlider ).hasClass( 'slick-initialized' ) ) {
							$j( _mainImageSlider ).slick( 'slickGoTo', parseInt( 0 ) );							
						}

						// change the main image
						$product_img.wc_set_variation_attr( 'src', variation.image.src );
						$product_img.wc_set_variation_attr( 'height', variation.image.src_h );
						$product_img.wc_set_variation_attr( 'width', variation.image.src_w );
						$product_img.wc_set_variation_attr( 'srcset', variation.image.srcset );
						$product_img.wc_set_variation_attr( 'sizes', variation.image.sizes );
						$product_img.wc_set_variation_attr( 'title', variation.image.title );
						$product_img.wc_set_variation_attr( 'alt', variation.image.alt );
						$product_img.wc_set_variation_attr( 'data-src', variation.image.full_src );
						$product_img.wc_set_variation_attr( 'data-large_image', variation.image.full_src );
						$product_img.wc_set_variation_attr( 'data-large_image_width', variation.image.full_src_w );
						$product_img.wc_set_variation_attr( 'data-large_image_height', variation.image.full_src_h );
						$product_img_wrap.wc_set_variation_attr( 'data-thumb', variation.image.thumb_src );
						$product_link.wc_set_variation_attr( 'href', variation.image.full_src );
						
						mainImageZoom();
					
				}
			} );

			$form.on( 'reset_data', function() {

				var $product_img_wrap = $j( _productGallery )
						.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' )
						.eq( 0 ),
					$product_img      = $j( _productGallery.querySelector( '.wp-post-image' ) ),
					$product_link     = $product_img_wrap.find( 'a' ).eq( 0 ),
					variations        = $j( '.variations' ).find( 'tr' ).length,
					$selects          = $form.find( 'select' ),
					chosen_count      = 0;

				$selects.each( function() {

					var value = $j( this ).val() || '';

					if ( value.length ) {
						chosen_count ++;
					}
				} );

				if ( variations > 1 && chosen_count == variations ) {
					$j( _mainImageSlider ).slick( 'slickGoTo', 0 );
				}

				// reset image
				$product_img.wc_reset_variation_attr( 'src' );
				$product_img.wc_reset_variation_attr( 'width' );
				$product_img.wc_reset_variation_attr( 'height' );
				$product_img.wc_reset_variation_attr( 'srcset' );
				$product_img.wc_reset_variation_attr( 'sizes' );
				$product_img.wc_reset_variation_attr( 'title' );
				$product_img.wc_reset_variation_attr( 'alt' );
				$product_img.wc_reset_variation_attr( 'data-src' );
				$product_img.wc_reset_variation_attr( 'data-large_image' );
				$product_img.wc_reset_variation_attr( 'data-large_image_width' );
				$product_img.wc_reset_variation_attr( 'data-large_image_height' );
				$product_img_wrap.wc_reset_variation_attr( 'data-thumb' );
				$product_link.wc_reset_variation_attr( 'href' );
				
				mainImageZoom();
				
			} );
		}

		var openPhotoSwipe = function( index ) {

			var pswpElement = document.querySelectorAll( '.pswp' )[0];

			// build item array
			var items = getImages();

			if ( $j( 'body' ).hasClass( 'rtl' ) ) {
				index = items.length - index - 1;
				items = items.reverse();
			}

			var options = {
				history              : false,
				showHideOpacity      : true,
				hideAnimationDuration: 333,
				showAnimationDuration: 333,
				index                : index
			};

			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
			gallery.init();
		};

		var getImages = function() {

			var items = [];

			[].slice.call( _mainImageSlider.querySelectorAll( 'a > img' ) )
			  .forEach( function( _img ) {

				  var src    = _img.getAttribute( 'data-large_image' ),
					  width  = _img.getAttribute( 'data-large_image_width' ),
					  height = _img.getAttribute( 'data-large_image_height' );

				  if ( ! $j( _img ).closest( '.woocommerce-product-gallery__image' ).hasClass( 'slick-cloned' ) ) {

					  items.push( {
						  src  : src,
						  w    : width,
						  h    : height,
						  title: false
					  } );
				  }

			  } );

			return items;
		};

		var getImageIndex = function( e ) {

			if ( _mainImageSlider.classList.contains( 'slick-slider' ) ) {
				return parseInt( _mainImageSlider.querySelector( '.slick-current' )
												 .getAttribute( 'data-slick-index' ) );
			} else {
				return $j( e.currentTarget ).parent().index();
			}
		};

		var scrollToReviews = function() {

			if ( ! $j( '#reviews' ).length ) {
				return;
			}

			$body.on( 'click', '.woocommerce-review-link', function( e ) {

				e.preventDefault();

				var scrollTo = $j( '#reviews' ).offset().top;
				
				$j( 'html, body' ).animate( {
					scrollTop: scrollTo,
				}, 600 );
			} );
		};

		var upSellsandRelated = function() {

			if ( $j( '.upsells .products' ).find( '.product' ).length < 4 && $j( '.related .products' )
																				.find( '.product' ).length < 4 ) {
				return;
			}

			$j( '.upsells .products, .related .products' ).slick( {
				slidesToShow  : 4,
				slidesToScroll: 4,
				dots          : true,
				responsive    : [{
					breakpoint: 992,
					settings  : {
						slidesToShow  : 3,
						slidesToScroll: 3,
					},
				}, {
					breakpoint: 768,
					settings  : {
						slidesToShow  : 2,
						slidesToScroll: 2,
					},
				}, {
					breakpoint: 544,
					settings  : {
						dots          : false,
						adaptiveHeight: true,
						centerPadding : '40px',
						centerMode    : true,
						slidesToShow  : 1,
						slidesToScroll: 1,
					},
				},],
			} );
		};

		// sticky details product page
		var stickyDetails = function() {
			
			var $details = $j( '.bew-sticky' );
			
			$body.trigger( 'sticky_kit:recalc' );

			if ( $window.width() < 992 ) {
				return;
			}

			if ( ! $details.length ) {
				return;
			}
			
			var rect = _productGallery.getBoundingClientRect(),
				left = rect.right,
				top  = 60;

			if ( $j( '#wpadminbar' ).length ) {
				top += $j( '#wpadminbar' ).height();
			}

			if ( $j( '.sticky-header' ).length ) {
				top += $j( '.sticky-header' ).height();
			}

			$details.stick_in_parent( { offset_top: top } ).on( 'sticky_kit:stick', function() {
				$j( this ).removeClass( 'sticky_kit-bottom' ).css( {
					'left': left,
					'top' : top,
				} );
			} ).on( 'sticky_kit:unstick', function() {
				$j( this ).removeClass( 'sticky_kit-bottom' ).css( {
					'left': 'auto',
					'top' : 'auto',
				} );
			} ).on( 'sticky_kit:bottom', function() {
				$j( this ).addClass( 'sticky_kit-bottom' ).css( {
					'left': $j( _productGallery ).outerWidth(),
				} );
			} ).on( 'sticky_kit:unbottom', function() {
				$j( this ).removeClass( 'sticky_kit-bottom' ).css( {
					'left': left,
					'top' : top,
				} );
			} );
		};
		
		//Tabs.		
		var tabs = function() {	
			
			var bewtabs = $j( '.bew-woo-tabs' );
			
				bewtabs.each( function() {
								
				$('.wc-tab:first', this).show();
				
				} );

		};

		mainImageSlider();
		mainImageZoom();
		thumbnailsSlider();
		lightBoxHandler();
		variationHandler();
		upSellsandRelated();
		scrollToReviews();
		stickyDetails();
		tabs();

		$window.scroll( function() {

			var viewportHeight = $j( window ).height();

			$j( _productGallery ).find( '.thumbnails > a' ).each( function() {
				var offsetThumbnails = $( this ).offset().top;

				if ( $window.scrollTop() > offsetThumbnails - viewportHeight + 20 ) {

					$j( this ).addClass( 'animate-images' );
				}

			} );
		} );

		$window.on( 'resize', function() {
			stickyDetails();
		} );
	
		
};
	

( function( $ ) {
	var bewslick = function( $scope, $ ) {
		
			 
		var $maincarousel = $scope.find( '.woocommerce-product-gallery__slider' ).eq(0),
			$thumbnailscarousel = $scope.find( '.thumbnails-slider' ).eq(0);
		
		if (!$('body').hasClass('elementor-editor-active')) {
			return;
		}
		
		if ( $maincarousel === null ) {
			return;
		}
				
		var main = $('.woocommerce-product-gallery__slider'),
			data_main_settings = main.data('carousel');
			
		if( typeof data_main_settings != 'undefined' && data_main_settings) {	
		var 	data_infinite = data_main_settings["infinite"];
		}
		
		var thumbnails = $('.thumbnails-slider'),
			data_thumbnails_settings = thumbnails.data('carousel');
			
		if( typeof data_thumbnails_settings != 'undefined' && data_thumbnails_settings) {	
		var	data_infinite_th = data_thumbnails_settings["infinite"];
		}	
		
		$maincarousel.slick({			
			dots: true,
			arrows: true,
			infinite: data_infinite,
			asNavFor: '.thumbnails-slider'
		} );
		
		$thumbnailscarousel.slick({			
			focusOnSelect:true,
			variableWidth:true,	
			slidesToShow: 3, 
			infinite: data_infinite_th,
			asNavFor: '.woocommerce-product-gallery__slider'  
		});	
		
		//Tabs.		
		
		var bewtabs = $j( '.bew-woo-tabs' );
			
		bewtabs.each( function() {		
			$('.wc-tabs li:first' , this).addClass('active');
			
			$( '.wc-tab, .woocommerce-tabs .panel:not(.panel .panel)' , this).hide();
			$('.wc-tab:first', this).show();
		
		} );
		
		
		// Star ratings for comments
	
		if ( $j( '.comment-form-rating p' ).hasClass( 'stars' ) ) {
		}else{	
		$j( '#rating' ).hide().before( '<p class="stars"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p>' );
		}

		// Zoom
		
		var _productGallery   = document.querySelector( '.woocommerce-product-gallery' ),
			_productGalleryDefault   = document.querySelector( '.bew-gallery-images' );
			
		if ( _productGallery === null || _productGalleryDefault  === null ) {
			return;
		}
				
		if ( _productGallery.classList.contains( 'product-zoom-on' ) || _productGalleryDefault.classList.contains( 'product-zoom-on' ) ) {

				var _zoomTarget  = $( '.woocommerce-product-gallery__image' ),
					_imageToZoom = _zoomTarget.find( 'img' );

				// But only zoom if the img is larger than its container.
				if ( _imageToZoom.attr( 'data-large_image_width' ) > _productGallery.offsetWidth ) {
					_zoomTarget.trigger( 'zoom.destroy' );
					_zoomTarget.zoom( {
						touch: false
					} );
				}
			}
			
	};
	
	// Make sure we run this code under Elementor
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew_dynamic.default', bewslick );
	} );
} )( jQuery );

