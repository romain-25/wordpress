jQuery( function ( $ ) {
	
	// Add header class to body after 300px
	$(window).bind( 'load scroll', function() {

		if ( $(window).scrollTop() > 300 ) {

			$( '#summary' ).addClass( 'fix-summary' );

		} else {

			$( '#summary' ).removeClass( 'fix-summary' );

		}

	});
	
	
	// woo modern summary
	var heightsummary;
	var heightsummaryinitial;
	var heightsummarytotal;		
	
	// Close Accordion
	$(document).one('ready', function(){
		heightsummarytotal = $('#summary').height()+382;
		
		$( '.elementor-accordion .elementor-tab-title' ).removeClass( 'elementor-active' );
		$( '.elementor-accordion .elementor-tab-content' ).css( 'display', 'none' );			
	});
	
	heightsummaryinitial = $('#summary').height()+382;
	heightsummary = heightsummaryinitial;
	
	// Remove fix-summary to body after 100px to the bottom
	$(window).scroll(function() {
		// change summary value
		if ( $( '.elementor-tab-title' ).hasClass( 'elementor-active' ) ) {  
				heightsummary =  heightsummarytotal; 
		} else {
				heightsummary = heightsummaryinitial;	
		}	
		  
		if($(window).scrollTop() > $(document).height()-heightsummary) {	  
			//you are at bottom		
			$('#summary').removeClass( 'fix-summary' );
			$( '#summary' ).addClass( 'bottom-summary' );	      
		} else {
			$( '#summary' ).removeClass( 'bottom-summary' );
		}
		
	});			

});