var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew templates
	bewtemplate();
} );

$j( window ).on( 'load', function() {
	"use strict";
	// Bew templates
	bewtemplate_oneditor();
} );


/* ==============================================
Bew Templates Manager
============================================== */	


function bewtemplate() {
	
	// Add bew template to create elementor template modal
	
	
	var html = '';		
		html +=		'<div id="bew-template_form_template-type_wrapper" class="bew-field">';
		html +=		'<label for="bew-template_form_template-type" class="bew-field_label">Select the type of BEW template</label>';
		html +=		'<div class="bew-field_select_wrapper">';
		html +=		'<select id="bew-template_form_template-type" class="bew-field_select" name="briefcase_template_layout">';
		html +=		'<option value="">Select...</option>';
		html +=		'<option value="woo-product">Woocommerce Single Product</option>';
		html +=		'<option value="woo-shop">Woocommerce Shop</option>'
		html +=		'<option value="woo-cat">Woocommerce Categories</option>';
		html +=		'<option value="edd-product">EDD Single Product</option>';
		html +=		'<option value="edd-shop">EDD Shop</option>';
		html +=		'</select>';
		html +=		'</div>';		
		html +=		'</div>';
		
		
		$j(".page-title-action:eq(0)").click(function(){	
					
		if ( $j( "#bew-template_form_template-type_wrapper" ).length == 0 ) {
			$j('#elementor-new-template__form__template-type__wrapper').append(html);
		}
		
		// Save select option on the session 
		$j("#elementor-new-template__form__submit").click(function(){		
			
			var selection = $j('.bew-field_select_wrapper select').find('option:selected').val();
			sessionStorage.setItem("selection", selection);	
			
			//Get the selection from session
			var sel2 = sessionStorage.getItem("selection");
			
			//Send the ajax request			
			var data = {
			'action': 'my_ajax_functionn', //the function in php functions to call
			'selection2':sel2		
			};		
			//send data to the php file admin-ajax which was stored in the variable ajaxurl
			$j.post(frontEndAjax.ajaxurl, data, function( response ) {
					   
			});	
		
		});
	
	
	});
	
		
};

function bewtemplate_oneditor() {
	
		//Get the selection from session
		var sel = sessionStorage.getItem("selection");
		
		// Remove all saved data from sessionStorage
		sessionStorage.clear();
						
		if(sel){
		//get the current ID
		var post_body = $j('body.single-elementor_library');
		var id = 0;

		if(post_body) {
        var classList = post_body.attr('class').split(/\s+/);
		
        $j.each(classList, function(index, item) {
            if (item.indexOf('postid') >= 0) {
                var item_arr = item.split('-');
                id =  item_arr[item_arr.length -1];
                return false;
            }
        });
		}		
		//Send the ajax request			
		var data = {
        'action': 'my_ajax_function', //the function in php functions to call
        'selection':sel,
		'id': id 
		};		
		//send data to the php file admin-ajax which was stored in the variable ajaxurl
		$j.post(frontEndAjax.ajaxurl, data, function( response ) {
         
        });		
		} 
	
};

