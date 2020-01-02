<?php
namespace Briefcase;

use Elementor;
use Elementor\Plugin;
use WP_Query;



class Helper{
	
	function get_bew_active_template($post_id,$post_type){
        $bew_product_template = get_post_meta($post_id, 'bew_post_template', true);

        if(isset($bew_product_template) && $bew_product_template == 'none'){
            return false;
        }

        if(!isset($bew_product_template) || empty($bew_product_template)){
            // apply global template
            $args = array(
                'post_type' => 'elementor_library',
                'meta_query' => array(
                    array(
                        'key' => 'briefcase_template_layout',
                        'value'   => $post_type,
                        'compare' => '='
                    )
                )
            );
            $templates = new WP_Query($args);
            if($templates->found_posts){
                $templates->the_post();
                $bew_tid = get_the_ID();
            }else{
                return false;
            }
            wp_reset_postdata();

        }else{
            // set individual post template
            $bew_tid = $bew_post_template;
        }

        return $bew_tid;
    }

	function get_woo_archive_template(){
			
                $args = array(
                    'post_type' => 'elementor_library',
                    'meta_query' => array(
						'relation'    => 'AND',
                        array(
                            'key' => 'briefcase_template_layout',
                            'value'   => 'woo-shop',
                            'compare' => '='
                        ),
						array(
                            'key' => 'briefcase_template_layout_shop',
                            'value'   => 'on',
                            'compare' => '='
                        ), 
                    )
                );
                $templates = new WP_Query($args);
				
                if($templates->found_posts){
                    $templates->the_post();					
                    $bew_tid = get_the_ID();
					
                }else{
                    return false;
                }
                wp_reset_postdata();
                return $bew_tid;
			
    }
	
	function get_woo_category_template(){	
			
        
            if(is_shop() || is_tax('product_cat') || is_product() || is_singular('elementor_library')|| is_page() || Elementor\Plugin::instance()->editor->is_edit_mode()){
                $args = array(
                    'post_type' => 'elementor_library',
                    'meta_query' => array(
						'relation'    => 'AND',
                        array(
                            'key' => 'briefcase_template_layout',
                            'value'   => 'woo-cat',
                            'compare' => '='
                        ),
						array(
                            'key' => 'briefcase_template_layout_cat',
                            'value'   => 'on',
                            'compare' => '='
                        ), 
                    )
                );
                $templates = new WP_Query($args);

                if($templates->found_posts){
                    $templates->the_post();
                    $bew_tid = get_the_ID();
                }else{
                    return false;
                }				
                wp_reset_postdata();
                return $bew_tid;
					
            }
        
        return false;
    }
	
	public function custom_description() {
		// Custom description callback
			add_filter( 'woocommerce_product_tabs', 'woo_custom_description_tab', 98 );
			function woo_custom_description_tab( $tabs ) {

				$tabs['description']['callback'] = 'woo_custom_description_tab_content';	// Custom description callback

				return $tabs;
			}

			function woo_custom_description_tab_content() {
				global $product, $post;
				$bewglobal = get_post_meta($post->ID, 'briefcase_apply_global', true);
				if (is_product() and $bewglobal == 'off' ) {
				
				echo 'este es custom descrition';
				} else {
					
				echo $product->get_description();
				}
			}
		}

	public static function get_templates() {
		return Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
	}
	
	/**
		 * Get Product Data for the current product
		 *
		 * @since 1.0.0
		 */
	public static function product_data() {
			
			global $product;			
				
			// Show firts product for loop template				
			if(empty($product)){
				// Todo:: Get product from template meta field if available
					$args = array(
						'post_type' => 'product',
						'post_status' => 'publish',
						'posts_per_page' => 1
					);
					$preview_data = get_posts( $args );
					$product_data =  wc_get_product($preview_data[0]->ID);
				
					$product = $product_data; 
							
				
			}
		return $product;
	}
	
	
	/**
	* Calculate sale percentage
	*
	* @param $product
	*
	* @return float|int
	*/
	public static function get_sale_percentage( $product ) {
			$percentage    = 0;
			$regular_price = $product->get_regular_price();
			$sale_price    = $product->get_sale_price();

			if ( $product->get_regular_price() ) {
				$percentage = - round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
			}

			return $percentage . '%';
		}
		
		
	public static function is_briefcasewp_extras_installed() {
		$file_path = 'briefcasewp-extras/briefcasewp-extras.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
	
	public function bew_get_templates_id($template_slug) {
		
		// Get unique domain name
		$url = get_site_url();
		$parseUrl = parse_url($url);
		$name = str_replace(['.', '-', '_'], '', $parseUrl['host']);		
		$path = str_replace(['/', '-'], ['_', ''], $parseUrl['path']);
						
		$unique_name = "my_shop_query_result_" .$name . $path;	
		
		if( $this->is_briefcasewp_extras_installed()){
			
			if(Elementor\Plugin::instance()->editor->is_edit_mode()){	
			// Get the bew templates
			
			// Get session to verify changes on briefcasewp templates
				if(!isset($_SESSION)){ 
				session_start();
				}
				if (isset($_SESSION['verify_templates_shop_render'])) {
				$verify_templates_shop_render = $_SESSION['verify_templates_shop_render'];				
				}
								
				//No cache
				if(empty($verify_templates_shop_render) ) {
					
					//This is the super slow query.	
					$templates_query = new \WP_Query(
						[
							'post_type' => 'elementor_library',
							'post_status' => 'publish',
							'posts_per_page' => -1,
							'orderby' => 'date',
							'order' => 'ASC',
							'meta_query'  => [
								'relation' => 'OR',
								[
									'relation' => 'AND',
									[
										'key' => '_elementor_template_type',
										'value' => 'briefcasewp',
									],					
									[
										'key' => 'briefcase_template_layout',
										'value' => 'woo-shop',
									],
								],
								[
									'relation' => 'AND',
									[
										'key' => '_elementor_template_type',
										'value' => 'page',
									],
									[
										'key' => 'briefcase_template_layout',
										'value' => 'woo-shop',
									],
								],
							],
						]
					);
					
					$templates = $templates_query;				
					$dataTemplates = [];
					
					foreach ( $templates->get_posts() as $post ) {		
					$template_title  = sanitize_title($post->post_title);
					$template_id  	 = $post->ID;		
					$dataTemplates[] = array("id"=>$template_id ,"title"=>$template_title);
					}
					wp_reset_postdata();
					
					//Cache it!		
					if (!get_site_option($unique_name)) {
					add_site_option( $unique_name, $dataTemplates);		
					}else {				
					update_site_option($unique_name, $dataTemplates);	
					}
									
					// Save session to verify changes on briefcasewp templates
					if(!isset($_SESSION)){ 
					session_start();
					}
					$verify_templates_shop_render = count($dataTemplates);
					$_SESSION['verify_templates_shop_render'] = $verify_templates_shop_render;
					
				} else {
					//Get data from Cache					
					$dataTemplates = get_site_option($unique_name);						
				}
			
			}else {	
					$dataTemplates = get_site_option($unique_name);
			}
						
			if(! empty($dataTemplates)){
				sort($dataTemplates);		
				$key = array_search($template_slug,array_column($dataTemplates,'title'));		
				$dataTemplate = $dataTemplates[$key];
				$dataTemplate_id  = $dataTemplate['id'];	
			}
			
			$template_id = $dataTemplate_id;
						
		}else{
			$template_id = $template_slug;	
		}
		
		
		return $template_id; 
	}
	
	public function bew_get_templates_cat_id($template_slug) {
		
		// Get unique domain name
		$url = get_site_url();
		$parseUrl = parse_url($url);
		$name = str_replace(['.', '-', '_'], '', $parseUrl[host]);		
		$path = str_replace(['/', '-'], ['_', ''], $parseUrl[path]);
						
		$unique_name_cat = "my_cat_query_result_" .$name . $path;
		
		if( $this->is_briefcasewp_extras_installed()){					
			
			if(Elementor\Plugin::instance()->editor->is_edit_mode()){	
			// Get the bew templates
			
			// Get session to verify changes on briefcasewp templates
				if(!isset($_SESSION)){ 
				session_start();
				}
				if (isset($_SESSION['verify_templates_cat_render'])) {
				$verify_templates_cat_render = $_SESSION['verify_templates_cat_render'];				
				}
								
				//No cache
				if(empty($verify_templates_cat_render) ) {
					
					//This is the super slow query.	
					$templates_query = new \WP_Query(
						[
							'post_type' => 'elementor_library',
							'post_status' => 'publish',
							'posts_per_page' => -1,
							'orderby' => 'date',
							'order' => 'ASC',
							'meta_query'  => [
								'relation' => 'OR',
								[
									'relation' => 'AND',
									[
										'key' => '_elementor_template_type',
										'value' => 'briefcasewp',
									],					
									[
										'key' => 'briefcase_template_layout',
										'value' => 'woo-cat',
									],
								],
								[
									'relation' => 'AND',
									[
										'key' => '_elementor_template_type',
										'value' => 'page',
									],
									[
										'key' => 'briefcase_template_layout',
										'value' => 'woo-cat',
									],
								],
							],
						]
					);
					
					$templates = $templates_query;				
					$dataTemplates = [];
					
					foreach ( $templates->get_posts() as $post ) {		
					$template_title  = sanitize_title($post->post_title);
					$template_id  	 = $post->ID;		
					$dataTemplates[] = array("id"=>$template_id ,"title"=>$template_title);
					}
					wp_reset_postdata();
					
					//Cache it!				
					if (!get_site_option($unique_name_cat)) {
					add_site_option( $unique_name_cat, $dataTemplates);		
					}else {				
					update_site_option($unique_name_cat, $dataTemplates);	
					}
					
					// Save session to verify changes on briefcasewp templates
					if(!isset($_SESSION)){ 
					session_start();
					}
					$verify_templates_cat_render = count($dataTemplates);
					$_SESSION['verify_templates_cat_render'] = $verify_templates_cat_render;
					
				} else {
					//Get data from Cache
							
					$dataTemplates = get_site_option($unique_name_cat);	
				}
					
			}else {			
			$dataTemplates = get_site_option($unique_name_cat);
					
			}
						
			if(! empty($dataTemplates)){
				sort($dataTemplates);		
				$key = array_search($template_slug,array_column($dataTemplates,'title'));		
				$dataTemplate = $dataTemplates[$key];
				$dataTemplate_id  = $dataTemplate['id'];	
			}			
						
			$template_id = $dataTemplate_id;
						
		}else{
			$template_id = $template_slug;	
		}	
		
		return $template_id; 
	}
	
	public function bew_get_templates_id_no_cache($template_slug) {
						
		if( $this->is_briefcasewp_extras_installed()){
				
			// Get the bew templates
				$templates_query = new \WP_Query(
					[
						'post_type' => 'elementor_library',
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'orderby' => 'date',
						'order' => 'ASC',
						'meta_query'  => [
							'relation' => 'OR',
							[
								'relation' => 'AND',
								[
									'key' => '_elementor_template_type',
									'value' => 'briefcasewp',
								],					
								[
									'key' => 'briefcase_template_layout',
									'value' => 'woo-shop',
								],
							],
							[
								'relation' => 'AND',
								[
									'key' => '_elementor_template_type',
									'value' => 'page',
								],
								[
									'key' => 'briefcase_template_layout',
									'value' => 'woo-shop',
								],
							],
						],
					]
				);
					
				$templates = $templates_query;				
				$dataTemplates = [];
				
				foreach ( $templates->get_posts() as $post ) {		
				$template_title  = sanitize_title($post->post_title);
				$template_id  	 = $post->ID;		
				$dataTemplates[] = array("id"=>$template_id ,"title"=>$template_title);
				}
				wp_reset_postdata();
									
			if(! empty($dataTemplates)){
				sort($dataTemplates);		
				$key = array_search($template_slug,array_column($dataTemplates,'title'));		
				$dataTemplate = $dataTemplates[$key];
				$dataTemplate_id  = $dataTemplate['id'];	
			}
			
			$template_id = $dataTemplate_id;
						
		}else{
			$template_id = $template_slug;	
		}	
		
		return $template_id; 
	}
	
	public function bew_get_templates_cat_id_no_cache($template_slug) {		
		
		if( $this->is_briefcasewp_extras_installed()){					
				
			// Get the bew templates
				
			//This is the super slow query.	
				$templates_query = new \WP_Query(
					[
						'post_type' => 'elementor_library',
						'post_status' => 'publish',
						'posts_per_page' => -1,
						'orderby' => 'date',
						'order' => 'ASC',
						'meta_query'  => [
							'relation' => 'OR',
							[
								'relation' => 'AND',
								[
									'key' => '_elementor_template_type',
									'value' => 'briefcasewp',
								],					
								[
									'key' => 'briefcase_template_layout',
									'value' => 'woo-cat',
								],
							],
							[
								'relation' => 'AND',
								[
									'key' => '_elementor_template_type',
									'value' => 'page',
								],
								[
									'key' => 'briefcase_template_layout',
									'value' => 'woo-cat',
								],
							],
						],
					]
				);
					
				$templates = $templates_query;				
				$dataTemplates = [];
				
				foreach ( $templates->get_posts() as $post ) {		
				$template_title  = sanitize_title($post->post_title);
				$template_id  	 = $post->ID;		
				$dataTemplates[] = array("id"=>$template_id ,"title"=>$template_title);
				}
				wp_reset_postdata();
									
			if(! empty($dataTemplates)){
				sort($dataTemplates);		
				$key = array_search($template_slug,array_column($dataTemplates,'title'));		
				$dataTemplate = $dataTemplates[$key];
				$dataTemplate_id  = $dataTemplate['id'];	
			}			
						
			$template_id = $dataTemplate_id;
						
		}else{
			$template_id = $template_slug;	
		}	
		
		return $template_id; 
	}
	
	/**
	 * Get base shop page link
	 *
	 * @param bool $keep_query
	 *
	 * @return false|string|void|WP_Error
	 */
	public static function get_shop_page_link( $keep_query = false ) {

		// Base Link decided by current page
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$link = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		}

		if ( $keep_query ) {

			// Keep query string vars intact
			foreach ( $_GET as $key => $val ) {

				if ( 'orderby' === $key || 'submit' === $key || 'product-page' === $key ) {
					continue;
				}

				$link = add_query_arg( $key, $val, $link );
			}
		}

		return $link;
	}
	
	public static function is_shop() {
			return ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() );
	}

}

