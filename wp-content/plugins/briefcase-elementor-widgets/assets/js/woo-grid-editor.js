jQuery( function ( $ ) {
	
$(document).ready(function (){	
	
    elementor.hooks.addAction( 'panel/open_editor/widget', function( panel, model, view ) {
        var widget_type = model.attributes.widgetType;
        if(widget_type == 'bew-woo-grid' || widget_type == 'bew-fullpage' || widget_type == 'bew-edd-grid' ){
			
			// Add button to edit template
			
			$('#bb').on( 'click', function() {
				
				var data = $('.elementor-control-template_id select').find('option:selected').val();
				var templateID = data;
				var pathname   = window.location.pathname				
				var url        = window.location.protocol + "//" + window.location.host; 
				var editUrl    = url + '/?p=' + templateID + '&elementor';
				
				window.open(editUrl, '_blank');				
				
			});

        }
    } );
		
});


});