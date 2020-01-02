jQuery( function ( $ ) {


$(document).ready(function() {
    if ($(".bew-woo-grid").length > 0 ) {
      $(window).load(function() {   
        $(".bew-woo-grid").addClass("show-bew-woo-grid"); 

      });
    }
 })
  
$(document).ready(function() {
    if ($(".bew-woo-grid-filter").length > 0 ) {
      $(window).load(function() {   
        $(".bew-woo-grid-filter").addClass("show-bew-woo-grid"); 

      });
    }
}) 

$(document).ready(function() {
    if ($(".bew-woo-grid-slider").length > 0 ) {
      $(window).load(function() {   
        $(".bew-woo-grid-slider").addClass("show-bew-woo-grid"); 

      });
    }
}) 


$(document).ready(function() {
    if ($("#bew-animates").length > 0 && $("#bew-animates").css("display") != "none") {
      $(window).load(function() {   
        $("#woo-grid-loader .bew-loader-content").fadeOut();
        $("#bew-animates").delay(450).fadeOut("slow");         
      });
    }
  })
  
$(document).ready(function (){	

	//Grid Animation for columns switcher
	const grid = document.querySelector(".products");
	
	if(grid && typeof animateCSSGrid != 'undefined'){
	animateCSSGrid.wrapGrid(grid, { duration: 350, stagger: 10 });	
	}

	
});


});