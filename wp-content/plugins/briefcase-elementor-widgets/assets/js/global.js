
( function( $ ) {
	
	// Menu dropdown horizontal.
	
				
				$('#menu-horizontal').click(function () {
				if ( $( '#menu-horizontal div' ).hasClass( "elementor-active" ) ) {
  
				$('#logo-menu').addClass('hide-logo');    
				} else {
				$('#logo-menu').removeClass('hide-logo'); 	
				}
				});	
	
	// Add title class to body after 40px
	$(window).bind( 'load scroll', function() {

		if ( $(window).scrollTop() > 40 ) {

			
			$( '#title-page' ).addClass( 'hide-title-page' );

		} else {
			
			
			$( '#title-page' ).removeClass( 'hide-title-page' );

		}

	});
	
		
	// Add header class to body when is botton page
	 $(window).scroll(function() {   
		
	});
	
			// Hide Header on on scroll down
			var didScroll;
			var lastScrollTop = 0;
			var delta = 5;
			var navbarHeight = $('#header-page').outerHeight();

			$(window).scroll(function(event){
				didScroll = true;
			});

			setInterval(function() {
				if (didScroll) {
					hasScrolled();
					didScroll = false;
				}
			}, 250);

			function hasScrolled() {
				var st = $(this).scrollTop();
				
				// Make sure they scroll more than delta
				if(Math.abs(lastScrollTop - st) <= delta)
					return;
				
				// If they scrolled down and are past the navbar, add class .nav-up.
				// This is necessary so you never see what is "behind" the navbar.
				if (st > lastScrollTop && st > navbarHeight){
					// Scroll Down
					if($(window).scrollTop() + $(window).height() > $(document).height()-90)  {
					$('#header-page').removeClass('nav-up').addClass('nav-down');
					} else {
					$('#header-page').removeClass('nav-down').addClass('nav-up');
					}	
				} else {
					// Scroll Up
					if(st + $(window).height() < $(document).height()) {
						$('#header-page').removeClass('nav-up').addClass('nav-down');
					}
				}
				
				lastScrollTop = st;
			}
	
} )( jQuery );

