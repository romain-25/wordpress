<?php
namespace Briefcase;

class bewtemplate{
	private static $_instance = null;
   

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {
		// Main Woo Filters
		
		add_action( 'admin_enqueue_scripts', array( $this, 'my_scripts' ) );		
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'my_scripts' ) );
		add_action( 'wp_ajax_nopriv_my_ajax_function', array( $this, 'my_ajax_function' ));
		add_action( 'wp_ajax_my_ajax_function', array( $this, 'my_ajax_function'  ));
		add_action( 'wp_ajax_nopriv_my_ajax_functionn', array( $this, 'my_ajax_functionn' ));
		add_action( 'wp_ajax_my_ajax_functionn', array( $this, 'my_ajax_functionn'  ));			
	}
	
function my_scripts(){
	
	// check to see if the $post type is 'elementor library'
	global $post;	
	$post_type = isset($post) ? $post->post_type : '';
	
	if(is_admin()){
	$screen = get_current_screen(); 
	$screen_elementor  = $screen->id;	
	}
	$screen_elementor = isset($screen_elementor) ? $screen_elementor : '';

	if ( 'elementor_library' == $post_type || 'edit-elementor_library' == $screen_elementor ) {
	
	//file where AJAX code will be found
		wp_enqueue_script( 'my-script-handle', plugins_url( '/assets/js/bew-template.js', __FILE__ ), [ 'jquery'], false, true ); 

	//passing variables to the javascript file
		wp_localize_script('my-script-handle', 'frontEndAjax', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' )
		));	
	} 
	
}


function my_ajax_function(){ 

//now we can get the data we passed via $_POST[]. make sure it isn't empty first.
if(! empty( $_POST['selection'] ) ){
   $my_selection = esc_html($_POST['selection']);
}
if(! empty( $_POST['id'] ) ){
   $my_id = esc_html($_POST['id']);
}

 echo $my_selection;
 echo $my_id;
  
 update_post_meta( $my_id, 'briefcase_template_layout', $my_selection); 
 
 wp_die();

}

function my_ajax_functionn(){ 

//now we can get the data we passed via $_POST[]. make sure it isn't empty first.
if(! empty( $_POST['selection2'] ) ){
   $my_selection2 = esc_html($_POST['selection2']);   
   session_start();
   $_SESSION['selss'] = $my_selection2;
   
}
  
  echo $_SESSION['selss'];
 
}

}
bewtemplate::instance();