var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew product filter
	bewfilter();
} );

// Make sure you run this code under Elementor..
$j( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew-woo-grid.default', function() {
		"use strict";
	// Bew product filter
	bewfilter();
	});
	} );
	

/* ==============================================
WOOCOMMERCE PRODUCT FILTER
============================================== */	


function bewfilter() {
	
	
	// product filter
	
		var $portfolio_selectors = $j('.product-filter >li>a');
		var $portfolio = $j('.products-items');
		$portfolio.isotope({
			itemSelector : '.products-item',
			layoutMode : 'fitRows'
		});
		
		$portfolio_selectors.on('click', function(){
			$portfolio_selectors.removeClass('active');
			$j(this).addClass('active');
			var selector = $j(this).attr('data-filter');
			$portfolio.isotope({ filter: selector });
			return false;
		});
		
		 // Change columns when click on elementor editor	
	if ($j( 'body' ).hasClass("elementor-editor-active")) {
		 
		var $div = $j(".elementor-widget-bew-woo-grid");
			
			var observer = new MutationObserver(function(mutations) {
			  mutations.forEach(function(mutation) {
				if (mutation.attributeName === "class") {
				  var attributeValue = $j(mutation.target).prop(mutation.attributeName);	
					
				//get the current columns value
				  var woo_grid = $j('.elementor-widget-bew-woo-grid');
				  var id = 0;	
				  var addClasses = '';

				  
				  if(woo_grid.length) {
					var classList = woo_grid.attr('class').split(/\s+/);
					
					$j.each(classList, function(index, item) {
						if (item.indexOf('bew-products-columns') >= 0) {
							var item_arr = item.split('columns-');
							id =  item_arr[item_arr.length -1];
							addClasses    += ' bew_span_1_of_' + id;
						}
					});
									
						
						
						$j('.products-item').removeClass (function (index, className) {
						return (className.match (/(^|\s)bew_span_1_of\S+/g) || []).join(' ');
						});
						
						$j('.products-item').addClass(addClasses);
						
						// get Isotope instance 
						var iso = $j('.products-items').data('isotope');
							console.log(iso);
						
						if($j('.products-items').length && iso  ) {
							console.log('dc');
							$j('.products-items').isotope('layout');
						}
				  }
				}
			  });
			});
			
		if($div.length) {
			observer.observe($div[0], {
			  attributes: true
			});
		}
	}
	
};