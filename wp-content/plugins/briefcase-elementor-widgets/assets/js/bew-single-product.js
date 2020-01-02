var $j = jQuery.noConflict();

// Make sure you run this code under Elementor..
$j( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew_dynamic.default', function() {
		"use strict";
	// Bew single product
	bewsingle();
	});
	} );
	

/* ==============================================
WOOCOMMERCE CUSTOM SINGLE PRODUCT
============================================== */	

function bewsingle() {		

	/**
	 * Product gallery class.
	 */
	var ProductGallery = function( $target, args ) {
		this.$target = $target;
		this.$images = $j( '.woocommerce-product-gallery__image', $target );

		// No images? Abort.
		if ( 0 === this.$images.length ) {
			this.$target.css( 'opacity', 1 );
			return;
		}

		// Make this object available.
		$target.data( 'product_gallery', this );

		// Pick functionality to initialize...
		this.flexslider_enabled = $j.isFunction( $j.fn.flexslider ) && wc_single_product_params.flexslider_enabled;
		this.zoom_enabled       = $j.isFunction( $j.fn.zoom ) && wc_single_product_params.zoom_enabled;
		this.photoswipe_enabled = typeof PhotoSwipe !== 'undefined' && wc_single_product_params.photoswipe_enabled;

		// ...also taking args into account.
		if ( args ) {
			this.flexslider_enabled = false === args.flexslider_enabled ? false : this.flexslider_enabled;
			this.zoom_enabled       = false === args.zoom_enabled ? false : this.zoom_enabled;
			this.photoswipe_enabled = false === args.photoswipe_enabled ? false : this.photoswipe_enabled;
		}

		// ...and what is in the gallery.
		if ( 1 === this.$images.length ) {
			this.flexslider_enabled = false;
		}

		// Bind functions to this.
		this.initFlexslider       = this.initFlexslider.bind( this );
		this.initZoom             = this.initZoom.bind( this );
		this.initZoomForTarget    = this.initZoomForTarget.bind( this );
		this.initPhotoswipe       = this.initPhotoswipe.bind( this );
		this.onResetSlidePosition = this.onResetSlidePosition.bind( this );
		this.getGalleryItems      = this.getGalleryItems.bind( this );
		this.openPhotoswipe       = this.openPhotoswipe.bind( this );

		if ( this.flexslider_enabled ) {
			this.initFlexslider();
			$target.on( 'woocommerce_gallery_reset_slide_position', this.onResetSlidePosition );
		} else {
			this.$target.css( 'opacity', 1 );
		}

		if ( this.zoom_enabled ) {
			this.initZoom();
			$target.on( 'woocommerce_gallery_init_zoom', this.initZoom );
		}

		if ( this.photoswipe_enabled ) {
			this.initPhotoswipe();
		}
	};

	/**
	 * Initialize flexSlider.
	 */
	ProductGallery.prototype.initFlexslider = function() {
		var $target = this.$target,
			gallery = this;

		var options = $j.extend( {
			selector: '.woocommerce-product-gallery__wrapper > .woocommerce-product-gallery__image',
			start: function() {
				$target.css( 'opacity', 1 );
			},
			after: function( slider ) {
				gallery.initZoomForTarget( gallery.$images.eq( slider.currentSlide ) );
			}
		}, wc_single_product_params.flexslider );

		$target.flexslider( options );

		// Trigger resize after main image loads to ensure correct gallery size.
		$j( '.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image:eq(0) .wp-post-image' ).one( 'load', function() {
			var $image = $j( this );

			if ( $image ) {
				setTimeout( function() {
					var setHeight = $image.closest( '.woocommerce-product-gallery__image' ).height();
					var $viewport = $image.closest( '.flex-viewport' );

					if ( setHeight && $viewport ) {
						$viewport.height( setHeight );
					}
				}, 100 );
			}
		} ).each( function() {
			if ( this.complete ) {
				$j( this ).trigger( 'load' );
			}
		} );
	};

	/**
	 * Init zoom.
	 */
	ProductGallery.prototype.initZoom = function() {
		this.initZoomForTarget( this.$images.first() );
	};

	/**
	 * Init zoom.
	 */
	ProductGallery.prototype.initZoomForTarget = function( zoomTarget ) {
		if ( ! this.zoom_enabled ) {
			return false;
		}

		var galleryWidth = this.$target.width(),
			zoomEnabled  = false;

		$j( zoomTarget ).each( function( index, target ) {
			var image = $j( target ).find( 'img' );

			if ( image.data( 'large_image_width' ) > galleryWidth ) {
				zoomEnabled = true;
				return false;
			}
		} );

		// But only zoom if the img is larger than its container.
		if ( zoomEnabled ) {
			var zoom_options = $j.extend( {
				touch: false
			}, wc_single_product_params.zoom_options );

			if ( 'ontouchstart' in window ) {
				zoom_options.on = 'click';
			}

			zoomTarget.trigger( 'zoom.destroy' );
			zoomTarget.zoom( zoom_options );
		}
	};

	/**
	 * Init PhotoSwipe.
	 */
	ProductGallery.prototype.initPhotoswipe = function() {
		if ( this.zoom_enabled && this.$images.length > 0 ) {
			this.$target.prepend( '<a href="#" class="woocommerce-product-gallery__trigger">ðŸ”</a>' );
			this.$target.on( 'click', '.woocommerce-product-gallery__trigger', this.openPhotoswipe );
		}
		this.$target.on( 'click', '.woocommerce-product-gallery__image a', this.openPhotoswipe );
	};

	/**
	 * Reset slide position to 0.
	 */
	ProductGallery.prototype.onResetSlidePosition = function() {
		this.$jtarget.flexslider( 0 );
	};

	/**
	 * Get product gallery image items.
	 */
	ProductGallery.prototype.getGalleryItems = function() {
		var $slides = this.$images,
			items   = [];

		if ( $slides.length > 0 ) {
			$slides.each( function( i, el ) {
				var img = $( el ).find( 'img' ),
					large_image_src = img.attr( 'data-large_image' ),
					large_image_w   = img.attr( 'data-large_image_width' ),
					large_image_h   = img.attr( 'data-large_image_height' ),
					item            = {
						src  : large_image_src,
						w    : large_image_w,
						h    : large_image_h,
						title: img.attr( 'data-caption' ) ? img.attr( 'data-caption' ) : img.attr( 'title' )
					};
				items.push( item );
			} );
		}

		return items;
	};

	/**
	 * Open photoswipe modal.
	 */
	ProductGallery.prototype.openPhotoswipe = function( e ) {
		e.preventDefault();

		var pswpElement = $j( '.pswp' )[0],
			items       = this.getGalleryItems(),
			eventTarget = $j( e.target ),
			clicked;

		if ( eventTarget.is( '.woocommerce-product-gallery__trigger' ) || eventTarget.is( '.woocommerce-product-gallery__trigger img' ) ) {
			clicked = this.$target.find( '.flex-active-slide' );
		} else {
			clicked = eventTarget.closest( '.woocommerce-product-gallery__image' );
		}

		var options = $j.extend( {
			index: $( clicked ).index()
		}, wc_single_product_params.photoswipe_options );

		// Initializes and opens PhotoSwipe.
		var photoswipe = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options );
		photoswipe.init();
	};

	/**
	 * Function to call wc_product_gallery on jquery selector.
	 */
	$j.fn.wc_product_gallery = function( args ) {
		new ProductGallery( this, args );
		return this;
	};

	/*
	 * Initialize all galleries on page.
	 */
	$j( '.woocommerce-product-gallery' ).each( function() {
		$j( this ).wc_product_gallery();
	} );
	
	
	
	};
