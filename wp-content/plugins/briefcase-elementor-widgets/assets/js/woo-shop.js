'use strict';

var wooshop;

(
	function() {

		wooshop = (
		function() {

				return {

					init: function() { 
						this.shop();
					}
				}
			}()
		);
	}
)( jQuery );

jQuery( document ).ready( function() {
	if (!jQuery( 'body' ).hasClass("elementor-editor-active")) {
		wooshop.init();
	}
} );


// Make sure you run this code under Elementor..
jQuery( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew-woo-grid.default', function() {
		if (jQuery( 'body' ).hasClass("elementor-editor-active")) {
			wooshop.init();
		}
	});
} );

// Shop page
(function( $ ) {

	var $window   = $( window ),
		$document = $( document ),
		$body     = $( 'body' ),
		w         = $window.width();		

	wooshop.shop = function() {

		var $products = $( '.products' ),
			$carousel = $( '.categories-carousel' );
		
		if ( ! $products.length ) {
			return;
		}

		var productCategoriesWidget = function() {
			
			if( typeof wooshopConfigs != 'undefined' && wooshopConfigs) {	
				if ( ! wooshopConfigs.categories_toggle ) {
					return;
				}
			}

			var _categoriesWidget = document.querySelector( '.widget_product_categories .product-categories' );

			if ( _categoriesWidget ) {
				_categoriesWidget.classList.add( 'has-toggle' );
			}

			// widget product categories accordion
			var _childrens = [].slice.call( document.querySelectorAll( '.widget_product_categories ul li ul.children' ) );

			if ( _childrens ) {

				_childrens.forEach( function( _children ) {

					var _i = document.createElement( 'i' );
					_i.classList.add( 'fa' );
					_i.classList.add( 'fa-angle-down' );

					_children.parentNode.insertBefore( _i, _children.parentNode.firstChild );
				} );
			}

			var _toggles = [].slice.call( document.querySelectorAll( '.widget_product_categories ul li.cat-parent i' ) );

			_toggles.forEach( function( _toggle ) {

				_toggle.addEventListener( 'click', function() {

					var _parent = _toggle.parentNode;

					if ( _parent.classList.contains( 'expand' ) ) {
						_parent.classList.remove( 'expand' );
						$( _parent ).children( 'ul.children' ).slideUp( 200 );
					} else {
						_parent.classList.add( 'expand' );
						$( _parent ).children( 'ul.children' ).slideDown( 200 );
					}
				} );
			} );

			var _parents = [].slice.call( document.querySelectorAll( '.widget_product_categories ul li.cat-parent' ) );

			_parents.forEach( function( _parent ) {

				if ( _parent.classList.contains( 'current-cat' ) || _parent.classList.contains( 'current-cat-parent' ) ) {
					_parent.classList.add( 'expand' );
					$( _parent ).children( 'ul.children' ).show();
				} else {
					$( _parent ).children( 'ul.children' ).hide();
				}

				$( '.widget_product_categories li.cat-parent.expand' ).find( '> ul.children' ).show();
			} );
			
			// Woo remove brackets from categories and filter widgets
						
			if ( $body.hasClass("oceanwp-theme")){
				
			} else{ 
				$( '.widget_layered_nav span.count, .widget_product_categories span.count' ).each( function() {
				var count = $( this ).html();
				count = count.substring( 1, count.length-1 );
				$( this ).html( count );
				} );
			}
		};
		
		var columnSwitcher = function() {

			if ( ! $( '.col-switcher' ).length ) {
				return;
			}
			
			if ($body.hasClass("elementor-editor-active")) {
			 var _editor = 'active'; 
			}			

			if(_editor != 'active'){
			addActiveClassforColSwitcher();
			}
			
			var $colSwitcher = $( '.col-switcher' ),
				$grid     = $( '.products' );
				
				

			$body.on( 'click', '#page-container', function( e ) {

				var $target = $( e.target ).closest( '.col-switcher' );

				if ( ! $target.length ) {
					$colSwitcher.removeClass( 'open' );
				}
			} );

			// Change columns when click
			$colSwitcher.find( 'a' ).unbind( 'click' ).on( 'click', function( e ) {

				e.preventDefault();

				var $this         = $( this ),
					windowWidth   = $window.width(),
					col           = $this.attr( 'data-col' ),
					removeClasses = '',
					addClasses    = '',
					addClasses2    = '';
				
				if(_editor != 'active'){
				// save cookie
				Cookies.set( 'bew_shop_col', col, {
					expires: 1,
					path   : '/'
				} );
				}				

				$colSwitcher.find( 'a' ).removeClass( 'active' );
				$this.addClass( 'active' );

				if ( windowWidth <= 544 ) {					
					addClasses = 'bew-products-count-' + col;					
					
				} else if ( windowWidth >= 545 && windowWidth <= 767 ) {					
					addClasses = 'bew-products-count-' + col;
				
				} else if ( windowWidth >= 768 && windowWidth <= 991 ) {					
					addClasses = 'bew-products-count-' + col;
					
				} else if ( windowWidth >= 992 && windowWidth <= 1024 ) {					
					addClasses = 'bew-products-count-' + col;
					
				} else if ( windowWidth >= 1025 ) {					
					addClasses = 'bew-products-count-' + col;
					
				}
				
				
				var el2 = document.querySelector('.products');
				el2.classList.forEach(className => {
				  if (className.startsWith('bew-products-count')) {
					el2.classList.remove(className);
				  }
				});
				
				$grid.addClass( addClasses );				
				$grid.data('columns', col);
				$grid.attr('data-columns', col);
				
				$products.trigger( 'arrangeComplete' );
				
			} );
			
			if(_editor != 'active'){
			if ( Cookies.get( 'bew_shop_col' ) ) {
				$colSwitcher.find( 'a[data-col="' + Cookies.get( 'bew_shop_col' ) + '"]' ).trigger( 'click' );
			}
			}
			
		};

		var filterDropdowns = function() {
			
			if( typeof wooshopConfigs != 'undefined' && wooshopConfigs) {	
				if ( ! wooshopConfigs.is_shop ) {
					return;
				}
			}

			$( '.widget_tm_layered_nav' ).on( 'change', 'select', function() {

				var slug       = $( this ).val(),
					href       = $( this ).attr( 'data-filter-url' ).replace( 'bew_FILTER_VALUE', slug ),
					pseudoLink = $( this ).siblings( '.filter-pseudo-link' );

				pseudoLink.attr( 'href', href );
				pseudoLink.trigger( 'click' );
			} );
		};

		var filtersArea = function() {
			if( typeof wooshopConfigs != 'undefined' && wooshopConfigs) {	
				if ( ! wooshopConfigs.is_shop ) {
					return;
				}
			}

			var _filters = document.querySelector( '.filters-area' );

			if ( _filters ) {
				$( _filters ).removeClass( 'filters-opened' ).stop().hide();
			}

			$( '.open-filters' ).unbind( 'click' ).on( 'click', function( e ) {
				e.preventDefault();

				var _filters = document.querySelector( '.filters-area' );

				if ( _filters.classList.contains( 'filters-opened' ) ) {
					closeFilters();
				} else {
					openFilters();
				}
			} );

			var openFilters = function() {

				var _filters   = document.querySelector( '.filters-area' ),
					_btnFilter = document.querySelector( '.open-filters' );

				_filters.classList.add( 'filters-opened' );
				$( _filters ).stop().slideDown( 300 );
				_btnFilter.classList.add( 'opened' );

				setTimeout( function() {

					$( '.filters-area .bew-layered-nav-filter ul.show-display-list' )
						.perfectScrollbar( { suppressScrollX: true } );

					$( '.filters-area .bew-layered-nav-filter ul.show-display-list.show-labels-off li' )
						.each( function() {
							$( this ).find( '.filter-swatch' ).removeClass( 'hint--top' ).addClass( 'hint--right' );
						} );
				}, 500 );
			};

			var closeFilters = function() {

				var _filters   = document.querySelector( '.filters-area' ),
					_btnFilter = document.querySelector( '.open-filters' );

				_filters.classList.remove( 'filters-opened' );
				$( _filters ).stop().slideUp( 300 );
				_btnFilter.classList.remove( 'opened' );
			};
		};
		
		var wooGridSlider = function() {
			
			/* Slider */
			
			$('.bew-products-slider').each(function(){
				if ( $(this).length > 0 ) {

					var slickInduvidual = $(this),
					slider_options 	= $(this).data('woo_grid_slider');
					
					slickInduvidual.not('.slick-initialized').slick(slider_options);
				}
			});	

			/* add id on add to cart button */				
				$('.slick-cloned').each(function(){
					$(this).find('.bew-add-to-cart').attr('id', 'bew-cart');
				});	
		};
			

		var events = function() {
			
			if ( $body.hasClass('oceanwp-theme' ) ) {
					
				} else {
					$( '.shop-filter select.orderby' ).niceSelect();
				}
						
			$( '.product-categories-select .list' ).perfectScrollbar();

			$( '.widget_tm_layered_nav ul.show-display-list' ).perfectScrollbar();

			$( '.widget_product_categories ul.product-categories' ).perfectScrollbar( { suppressScrollX: true } );

			productCategoriesWidget();

			columnSwitcher();
			
			filterDropdowns();

			filtersArea();	

			wooGridSlider();
		};
		
		var addActiveClassforColSwitcher = function() {

			var $colSwitcher = $( '.col-switcher' );

			if ( ! $colSwitcher.length ) {
				return;
			}

			var width  = $( '.products' ).width(),
				pWidth = $( '.product-loop' ).outerWidth(),
				col    = Cookies.get( 'bew_shop_col' ) ? Cookies.get( 'bew_shop_col' ) : Math.round( width / pWidth );

			$colSwitcher.find( 'a' ).removeClass( 'active' );
			$colSwitcher.find( 'a[data-col="' + col + '"]' ).addClass( 'active' );
		};
		
		events();	
		
	};
})( jQuery );


function filtersSearch(e) {
	
			// Declare variables
			  var id, filter, ul, li, ulchildren, lichildren, a, i, txtValue;			  
			  id = e.getAttribute('id');	
			  filter = e.value.toUpperCase();
			  
			  if (id == 'search-box-categories'){
				ul = document.getElementsByClassName("product-categories");  
			  }else{
				ul = document.getElementsByClassName("list-" + id);  
			  }			  
			  
			  li = ul[0].getElementsByTagName('li');
			  
			  for (i = 0; i < li.length; i++) {
			  if (li[i].getElementsByTagName("ul").length>0){
				 
				  ulchildren = document.getElementsByClassName("children");
				  lichildren = ulchildren[0].getElementsByTagName('li');	
			  } 			  
			  }
			  
			  // Loop through all list items, and hide those who don't match the search query
			  for (i = 0; i < li.length; i++) {
				a = li[i].getElementsByTagName("a")[0];
				txtValue = a.textContent || a.innerText;
				if (txtValue.toUpperCase().indexOf(filter) > -1) {
				  li[i].style.display = "";
				} else {
				  li[i].style.display = "none";
				}
			  }
			  
			  if (lichildren != undefined){
				  // Loop through all list children items, and hide those who don't match the search query
				  for (i = 0; i < lichildren.length; i++) {
					a = lichildren[i].getElementsByTagName("a")[0];
					txtValue = a.textContent || a.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
					  lichildren[i].style.display = "";
					  lichildren[i].closest("ul").style.display = "";
					  lichildren[i].closest(".cat-parent").style.display = "";
					} else {
					  lichildren[i].style.display = "none";
					}
				  }
			  }  
};
