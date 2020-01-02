'use strict';

var woomenucart;

(
	function() {

		woomenucart = (
		function() {

				return {

					init: function() {

						this.menucart();
					}
				}
			}()
		);
	}
)( jQuery );

jQuery( document ).ready( function() {
	woomenucart.init();
} );
	
// Search
(
	function( $ ) {

		var $body = $( 'body' );
						
		woomenucart.menucart = function() {
			var $menucart = $( '.woo-header-cart ' ),
				$minicart = $( '.bew-mini-cart-canvas' ),
				$closeBtn    = $( '.drawer__btn-close' );			
			    
			var events = function() {

				$menucart.on( 'click', '> .woo-menucart', function( e ) {

					e.preventDefault();

					openMenucart();
				} );
				
				$closeBtn.on( 'click', function() {
					closeMenucart();
				} );
				
				
				$minicart.on('click', function (event) {
					if ($minicart.hasClass('menucart--open') && $minicart[0] === event.target) {
						closeMenucart();
					}
				});

				$(document).keyup(function (event) {
					var ESC_KEY = 27;

					if (ESC_KEY === event.keyCode) {
						if ($minicart.hasClass('menucart--open')) {
							closeMenucart();
						}
					}
				});

			};

			events();

			var openMenucart = function() {

				$body.addClass( 'menu-canvas-enabled' );
				$minicart.addClass( 'menucart--open' );
				$closeBtn.removeClass( 'btn--hidden' );

			};

			var closeMenucart = function() {

				$body.removeClass( 'menu-canvas-enabled' );
				$minicart.removeClass( 'menucart--open' );
				$closeBtn.addClass( 'btn--hidden' );

			};
		}
	}
)( jQuery );