<?php
namespace Briefcase;

use Elementor;
use Elementor\Plugin;
use Elementor\Post_CSS_File;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow	;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Briefcase\Helper;
use Briefcase\Widgets\Classes\Products_Renderer;
use Elementor\Controls_Stack;

/**
 * Woo Grid Module
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bew_Widget_Woo_Grid extends Widget_Base {
	
	public function get_name() {
		return 'bew-woo-grid';
	}

	public function get_title() {
		return __( 'Woo Grid', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];
	}
	
	public function get_query() {
		return $this->query;
	}

	public function get_script_depends() {
		return [ 'woo-grid', 'isotope', 'imagesloaded','woo-product-filter', 'woo-shop' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	
	public static function get_templates() {
		return Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
	}
	
	public static function bew_get_templates() {		

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
		
		return $templates_query;
	}
	
	public static function bew_get_templates_cat() {		
	
		$templates_query = new \WP_Query(
			[
				'post_type' => 'elementor_library',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'title',
				'order' => 'DESC',
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
		
		return $templates_query;		
	}
	
	protected function bew_shop_templates_options() {
		
		// Options for Shop			
		$templates = $this->bew_get_templates();				
				
		if ( $templates->have_posts() ) {			
						
			foreach ( $templates->get_posts() as $post ) {
							
				$template_title  = sanitize_title($post->post_title);
							
				//Put Standard template firts
				if($template_title == 'standard-shop' ){
				$options[ 'standard-shop'  ] = ["image"=> BEW_EXTRAS_URL . 'extensions/bew-extensions/img/standard-shop.jpg',
												"title"=> 'standard-shop'
											   ];
				}
			}
						
			foreach ( $templates->get_posts() as $post ) {
							
				$template_title  = sanitize_title($post->post_title);

				$filename = BEW_EXTRAS_PATH . 'extensions/bew-extensions/img/' . $template_title . '.jpg';
							
				if(is_file($filename)){
					if($template_title != 'standard-shop' ){
					$options[ $template_title  ] = ["image"=> BEW_EXTRAS_URL . 'extensions/bew-extensions/img/' . $template_title . '.jpg',
																"title"=> $post->post_title
												   ];	
					}
								
				}else{
					if ( has_post_thumbnail( $post->ID ) ){
						$options[ $template_title  ] = ["image"=> get_the_post_thumbnail_url($post->ID,'full'),
														"title"=> $post->post_title
													   ];											
					} else {
						$options[ $template_title  ] = ["image"=> BEW_EXTRAS_URL . 'extensions/bew-extensions/img/bew-template-placeholder.jpg',
														"title"=> $post->post_title
													   ];						
					}
				}									
			}					
		}	
		
		return $options;	

	}
	
	protected function bew_cat_templates_options() {
								
		// Option for Categories				
		$templates = $this->bew_get_templates_cat();	
							
		if ( $templates->have_posts() ) {
					
			foreach ( $templates->get_posts() as $post ) {
						
				$template_title  = sanitize_title($post->post_title);

				$filename = BEW_EXTRAS_PATH . 'extensions/bew-extensions/img/' . $template_title . '.jpg';
							
				if(is_file($filename)){					
					$options_cat[ $template_title  ] = ["image"=> BEW_EXTRAS_URL . 'extensions/bew-extensions/img/' . $template_title . '.jpg',
														"title"=> $post->post_title
													   ];
				}else{
					if ( has_post_thumbnail( $post->ID ) ){
						$options_cat[ $template_title  ] = ["image"=> get_the_post_thumbnail_url($post->ID,'full'),
															"title"=> $post->post_title
														   ];											
					} else {
						$options_cat[ $template_title  ] = ["image"=> BEW_EXTRAS_URL . 'extensions/bew-extensions/img/bew-template-placeholder.jpg',
															"title"=> $post->post_title
														   ];						
					}
				}									
			}					
		}

		return $options_cat;	

	}
				
	public static function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . __( 'You Haven’t Saved Templates Yet.', 'elementor-pro' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . __( 'Want to learn more about Elementor library?', 'elementor-pro' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . __( 'Click Here', 'elementor-pro' ) . '</a>
				</div>
				</div>';
	}
	
	protected function grid_types(){
		//Check if we are on elementor single product template 
		global $post;
		$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);	
		
		if ( empty($bew_template_type) ){		
		return [
          'shop' => __('Shop Page', 'briefcase-elementor-widgets'),		  
		  'latest' => __('Latest Products', 'briefcase-elementor-widgets'),	
		  'featured' => __('Featured Products', 'briefcase-elementor-widgets'),	
		  'related' => __('Related Products', 'briefcase-elementor-widgets'),
          'upsell'  => __('Upsell Products', 'briefcase-elementor-widgets'),		  
		  'categories' => __('Categories', 'briefcase-elementor-widgets'),
        ];		
		} else{
			
			if ( $bew_template_type == 'woo-product' ){
			return [
			  'shop' => __('Shop Page', 'briefcase-elementor-widgets'),
			  'latest' => __('Latest Products', 'briefcase-elementor-widgets'),
			  'featured' => __('Featured Products', 'briefcase-elementor-widgets'),	
			  'related' => __('Related Products', 'briefcase-elementor-widgets'),
			  'upsell'  => __('Upsell Products', 'briefcase-elementor-widgets'),		  
			  'categories' => __('Categories', 'briefcase-elementor-widgets'),
			];
			} else {
			 return [
			  'shop' => __('Shop Page', 'briefcase-elementor-widgets'),
			  'latest' => __('Latest Products', 'briefcase-elementor-widgets'),
			  'featured' => __('Featured Products', 'briefcase-elementor-widgets'),	
			  'categories' => __('Categories', 'briefcase-elementor-widgets'),
			];
			}
			
		}				
		
    }
		
	protected function _register_controls() {
		
		$this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Woo Grid', 'briefcase-elementor-widgets' ),                
            ]
        );
		
		$this->add_control(
            'grid_type',
            [
                'label' => __('Woo Grid Type','briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => $this->grid_types(),
				'label_block'  => true,
                'default' => 'shop'
            ]
        );
				
		$helper = new Helper();
		
		if($helper->is_briefcasewp_extras_installed()){
			
			$woo_grid_cache = get_option('woo_grid_cache');	
			
			if($woo_grid_cache == 1){
				
				// Get unique name
				$url = get_site_url();
				$parseUrl = parse_url($url);
				if (isset($parseUrl)){
				$name = str_replace('.', '', $parseUrl['host']);			
				$path = str_replace('/', '_', $parseUrl['path']);
				}
				$unique_name = $name . $path;
							
				$templates_options_shop = "shop_templates_options_" . $unique_name;
				$templates_options_cat = "cat_templates_options_" . $unique_name;
							
				if(Elementor\Plugin::instance()->editor->is_edit_mode()){
						
					// Get session to verify changes on briefcasewp templates
					if(!isset($_SESSION)){ 
					session_start();
					}
					if (isset($_SESSION['verify_templates_shop'])) {
					$verify_templates_shop = $_SESSION['verify_templates_shop'];				
					}
					if (isset($_SESSION['verify_templates_cat'])) {				
					$verify_templates_cat = $_SESSION['verify_templates_cat'];
					}
												
					//No cache			
					// Options for Shop			
					if(empty($verify_templates_shop) ) {
							
						$options = $this->bew_shop_templates_options();
							
						// Save the shop templates options on WordPress Options API
																	
						if (!get_site_option($templates_options_shop)) {
						add_site_option( $templates_options_shop , $options);			
						}else {				
						update_site_option($templates_options_shop , $options);	
						}
							
						// Save session to verify changes on briefcasewp templates shop
						if(!isset($_SESSION)){ 
						session_start();
						}
						if(empty($options)){
						$verify_templates_shop = 0;	
						}else {
						$verify_templates_shop = count($options);	
						}						
						$_SESSION['verify_templates_shop'] = $verify_templates_shop;
								
					}else{
							
						$options  = get_site_option($templates_options_shop);					
					}				
						
					// Option for Categories
					if(empty($verify_templates_cat) ) {		
						
						$options_cat = $this->bew_cat_templates_options();
							
						// Save the cat templates options on WordPress Options API
						if (!get_site_option($templates_options_cat)) {
						add_site_option( $templates_options_cat , $options_cat);			
						}else {				
						update_site_option($templates_options_cat , $options_cat);	
						}
						// Save session to verify changes on briefcasewp templates
						if(!isset($_SESSION)){ 
						session_start();
						}
						if(empty($options_cat)){
						$verify_templates_cat = 0;	
						}else {
						$verify_templates_cat = count($options_cat);	
						}						
						$_SESSION['verify_templates_cat'] = $verify_templates_cat;
							
					}else{				
						$options_cat  = get_site_option($templates_options_cat);
					}						
						
				} else {
						//Frontend options
						$options 	  = get_site_option($templates_options_shop);
						$options_cat  = get_site_option($templates_options_cat);					
				}
						
			}else{				
				$options = $this->bew_shop_templates_options();								
				$options_cat = $this->bew_cat_templates_options();
			}			
			
		}else {
			$templates = $this->get_templates();
						
			$options = [
				'0' => '— ' . __( 'Select', 'briefcase-elementor-widgets' ) . ' —',
			];

			$types = [];

			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
				$types[ $template['template_id'] ] = $template['type'];
			}
		}
		
		if ( empty( $options ) ) {

		$this->add_control(
			'no_templates',
			[
				'label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => $this->empty_templates_message(),
			]
		);
		$empty_templates = "yes";

		}
		
		if ( empty( $empty_templates ) ) {
		
			if($helper ->is_briefcasewp_extras_installed()){
				$this->add_control(
					'template_type',
					[
						'label' => __( 'Choose Template', 'briefcase-elementor-widgets' ),
						'type' => 'chooseimagery',
						'default' => 'standard-shop',
						'options' => $options,				
						'label_block'  => true,
						'condition' => [
                    'grid_type!' => 'categories',					
                ]
					]
				);
				
				$this->add_control(
					'template_type_cat',
					[
						'label' => __( 'Choose Template', 'briefcase-elementor-widgets' ),
						'type' => 'chooseimagery',
						'default' => 'minimalism-categories',
						'options' => $options_cat,				
						'label_block'  => true,
						'condition' => [
                    'grid_type' => 'categories',					
                ]
					]
				);
				
			}else{
				$this->add_control(
					'template_id',
					[
						'label' => __( 'Choose Template', 'briefcase-elementor-widgets' ),
						'type' => Controls_Manager::SELECT,
						'default' => '0',
						'options' => $options,
						'types' => $types,
						'label_block'  => true,				
					]
				);	
			}
		
			$button = '<div class="elementor-button elementor-button-default elementor-edit-template-bew" id="bb"><i class="fa fa-pencil"></i> Edit Template</div>';
					
			$this->add_control(
				'field_preview',
				[
					'label'   => esc_html__( 'Code', 'briefcase-elementor-widgets' ),
					'type'    => Controls_Manager::RAW_HTML,
					'separator' => 'none',
					'show_label' => false,
					'raw' => $button,
				]
			);
			
			$this->add_control(
				'template_desc',
				[
					'label' => __( 'If you don’t have a custom template yet. It will use the default theme template ', 'briefcase-elementor-widgets' ),
					'type' => Controls_Manager::RAW_HTML,					
				]
			);		
		}
	
	
		$this->end_controls_section();				
		
		$this->start_controls_section(
            'section_categories',
            [
                'label' => __( 'Categories', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'grid_type' => 'categories',					
                ]
            ]
        );		

		$this->add_control(
			'source',
			[
				'label' => _x( 'Source', 'Posts Query Control', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Show All', 'elementor-pro' ),
					'by_id' => __( 'Manual Selection', 'briefcase-elementor-widgets' ),
					'by_parent' => __( 'By Parent', 'briefcase-elementor-widgets' ),
				],
				'label_block' => true,
			]
		);

		$categories = get_terms( 'product_cat' );

		$options = [];
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $options,
				'default' => [],
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'source' => 'by_id',
				],
			]
		);

		$parent_options = [ '0' => __( 'Only Top Level', 'briefcase-elementor-widgets' ) ] + $options;
		$this->add_control(
			'parent',
			[
				'label' => __( 'Parent', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $parent_options,
				'condition' => [
					'source' => 'by_parent',
				],
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label' => __( 'Hide Empty', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Hide',
				'label_off' => 'Show',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_woo_grid',
			[
				'label' 		=> __( 'Woo Grid Settings', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_responsive_control(
            'woo_grid_layout',
            [
                'label' => __( 'Woo Grid Layout', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'grid' => [
						'title' => __( 'Grid', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-th-large',
					],
					'slider' => [
						'title' => __( 'Slider', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-square',
					]                   
                ],
                'default' => 'grid',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],								
            ]
        );

		$this->add_control(
			'count',
			[
				'label' 		=> __( 'Elements Per Page', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'You can enter "-1" to display all elements.', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '8',
				'label_block' 	=> true,
				'condition' => [
                    'grid_type!' => ['categories'],
					'woo_grid_layout!' => ['slider'],
                ]
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Grid Columns', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'bew-products-columns%s-',
				'min' => 1,
				'max' => 12,
				'default' => 4,
				'required' => true,
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'frontend_available' => true,
				'condition' => [                    
					'woo_grid_layout!' => ['slider'],
                ]
			]
		);
		
		$this->add_control(
			'grid_style',
			[
				'label' 		=> __( 'Grid Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'fit-rows',
				'options' 		=> [
					'fit-rows' 	=> __( 'Fit Rows', 'briefcase-elementor-widgets' ),
					'masonry' 	=> __( 'Masonry', 'briefcase-elementor-widgets' ),
				],
				'condition' => [                    
					'woo_grid_layout!' => ['slider'],
                ]
			]
		);

		$this->add_control(
			'grid_equal_heights',
			[
				'label' 		=> __( 'Equal Heights', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'false',
				'options' 		=> [
					'yes' 		=> __( 'Yes', 'briefcase-elementor-widgets' ),
					'false' 	=> __( 'No', 'briefcase-elementor-widgets' ),
				],
				'condition' => [                    
					'woo_grid_layout!' => ['slider'],
                ]
			]
		);

		$this->add_control(
			'order',
			[
				'label' 		=> __( 'Order', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options' 		=> [
					'' 			=> __( 'Default', 'briefcase-elementor-widgets' ),
					'DESC' 		=> __( 'DESC', 'briefcase-elementor-widgets' ),
					'ASC' 		=> __( 'ASC', 'briefcase-elementor-widgets' ),
				],				
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' 		=> __( 'Order By', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '',
				'options' 		=> [
					'' 				=> __( 'Default', 'briefcase-elementor-widgets' ),
					'date' 			=> __( 'Date', 'briefcase-elementor-widgets' ),
					'title' 		=> __( 'Title', 'briefcase-elementor-widgets' ),
					'name' 			=> __( 'Name', 'briefcase-elementor-widgets' ),
					'modified' 		=> __( 'Modified', 'briefcase-elementor-widgets' ),
					'author' 		=> __( 'Author', 'briefcase-elementor-widgets' ),
					'rand' 			=> __( 'Random', 'briefcase-elementor-widgets' ),
					'ID' 			=> __( 'ID', 'briefcase-elementor-widgets' ),					
					'menu_order' 	=> __( 'Menu Order', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
                    'grid_type!' => ['categories' ]
                ]
			]
		);
				
		
		$this->add_control(
			'orderby_cat',
			[
				'label' => __( 'Order by', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => __( 'Name', 'briefcase-elementor-widgets' ),
					'slug' => __( 'Slug', 'briefcase-elementor-widgets' ),
					'description' => __( 'Description', 'briefcase-elementor-widgets' ),
					'count' => __( 'Count', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
                    'grid_type' => 'categories',
                ]
			]

		);
		
		$categories = get_terms( 'product_cat' );

		$options = [];
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'include_categories',
			[
				'label' 		=> __( 'Include Categories', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'You can select multiples categories', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $options,
				'default' => [],
				'label_block' 	=> true,
				'multiple' => true,
				'condition' => [
                    'grid_type' => [ 'shop', 'featured', 'latest'],
                ]
			]
		);

		$this->add_control(
			'exclude_categories',
			[
				'label' 		=> __( 'Exclude Categories', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'You can select multiples categories', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $options,
				'default' => [],
				'label_block' 	=> true,
				'multiple' => true,
				'label_block' 	=> true,
				'condition' => [
                    'grid_type' => [ 'shop', 'featured', 'latest'],
                ]
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_woo_grid_slider',
            [
                'label' => __( 'Woo Grid Slider', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'woo_grid_layout' => [ 'slider'],
                ]
            ]
        );
				
		$this->add_control(
			'woo_grid_slider_products_per_page',
			[
				'label'     => __( 'Total Products', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 8,				
			]
		);

		$this->add_responsive_control(
			'woo_grid_slides_to_show',
			[
				'label'          => __( 'Products to Show', 'briefcase-elementor-widgets' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 4,
				'tablet_default' => 3,
				'mobile_default' => 1,				
			]
		);

		$this->add_responsive_control(
			'woo_grid_slides_to_scroll',
			[
				'label'          => __( 'Products to Scroll', 'briefcase-elementor-widgets' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,				
			]
		);		
		
		$this->add_control(
			'woo_grid_slider_navigation_arrows',
			[
				'label' => __( 'Arrows', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',			
			]
		);
		
		$this->add_control(
			'woo_grid_slider_navigation_dots',
			[
				'label' => __( 'Dots', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'woo_grid_slider_autoplay',
			[
				'label'        => __( 'Autoplay', 'briefcase-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',				
			]
		);
		$this->add_control(
			'woo_grid_slider_autoplay_speed',
			[
				'label'     => __( 'Autoplay Speed', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'selectors' => [
					'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
				],
				'condition' => [				
					'woo_grid_slider_autoplay' => 'yes',
				],
			]
		);
		$this->add_control(
			'woo_grid_slider_pause_on_hover',
			[
				'label'        => __( 'Pause on Hover', 'briefcase-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'woo_grid_slider_autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'woo_grid_slider_infinite',
			[
				'label'        => __( 'Infinite Loop', 'briefcase-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',				
			]
		);

		$this->add_control(
			'woo_grid_slider_transition_speed',
			[
				'label'     => __( 'Transition Speed (ms)', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,				
			]
		);
		
		$this->end_controls_section();
				
		if ( empty( $empty_templates ) ) {
		global $post;
		$template_type = get_post_meta($post->ID, '_elementor_template_type', true);
		 
		
		if (is_shop() || is_product_category() || is_product_tag() || $template_type == 'product-archive' ) {
		$this->start_controls_section(
            'section_toolbar',
            [
                'label' => __( 'Toolbar and Pagination', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'grid_type' => [ 'shop'],
                ]
            ]
        );
		
		$this->add_control(
			'toolbar_show',
			[
				'label' 		=> __( 'Shop Toolbar', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'result_count',
			[
				'label' => __( 'Result Count', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'toolbar_show' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'col_switcher',
			[
				'label' => __( 'Column Switcher', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'toolbar_show' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'shop_filter',
			[
				'label' => __( 'Shop Filter', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'toolbar_show' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'filter_columns',
			[
				'label' => __( 'Filter Columns', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'bew-filters-columns%s-',
				'min' => 1,
				'max' => 12,				
				'required' => true,
				'device_args' => [
					Controls_Stack::RESPONSIVE_TABLET => [
						'required' => false,
					],
					Controls_Stack::RESPONSIVE_MOBILE => [
						'required' => false,
					],
				],
				'min_affected_device' => [
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET => Controls_Stack::RESPONSIVE_TABLET,
				],
				'condition' => [
					'shop_filter' => 'yes',
					'toolbar_show' => 'yes',
				],
			]
		);
		
		$repeater = new Repeater();
		
		$registered_filters      = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		$registered_filters['sort'] = __('sort', 'briefcase-elementor-widgets');
        $registered_filters['price'] = __('price','briefcase-elementor-widgets');
        $registered_filters['categories'] = __('categories','briefcase-elementor-widgets');
				
		if ( $attribute_taxonomies ) {
			foreach ( $attribute_taxonomies as $tax ) {
				$registered_filters[ $tax->attribute_name ] = $tax->attribute_name;
			}
		}
		
		$registered_filters['custom'] = __('custom','briefcase-elementor-widgets');
				
        $repeater->add_control(
            'filter_type',
            [
                'label' => __( 'Filter Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SELECT,
                'options' => $registered_filters,
                'default' => 'sort',
            ]
        );

        $repeater->add_control(
            'filter_title',
            [
                'label' => __( 'Filter Title', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Sort',
            ]
        );
		
		$repeater->add_control(
			'search_box',
			[
				'label' => __( 'Search Box', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
			]
		);
				
		$repeater->add_responsive_control(
            'filter_layout',
            [
                'label' => __( 'Layout', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'list' => [
                        'title' => __( 'List', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-v',
                    ],
					'inline' => [
                        'title' => __( 'Inline', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-h',
                    ]										                   
                ],
                'default' => 'list', 
				'devices' => [ 'desktop', 'tablet', 'mobile' ],				
				'conditions' => [
					'terms' => [						
						[
							'name' => 'filter_type',
							'operator' => '!=',
							'value' => 'sort',
						],
						[
							'name' => 'filter_type',
							'operator' => '!=',
							'value' => 'price',
						],
						[
							'name' => 'filter_type',
							'operator' => '!=',
							'value' => 'categories',
						],
					],
				],
                
            ]
        );
		
		$repeater->add_responsive_control(
			'filter_layout_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 100,					
				],
				'size_units' => [ '%' , 'px'],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .filter-list ul' => 'width: {{SIZE}}{{UNIT}};',
				],				
				'condition' => [
                    'filter_layout' => 'inline',
				]
			]
		);

        $repeater->add_control(
            'custom_filter',
            [
                'label' => __( 'Custom Filter', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::WYSIWYG,				
                'default' => '',
                'condition' => [
                    'filter_type' => 'custom',
                ],
            ]
        );
		
        $this->add_control(
            'filters',
            [
                'label' => __( 'Filters', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::REPEATER,
                'fields' => array_values($repeater->get_controls()),
                'default' => [
                    [
                        'filter_type' => 'sort',
                        'filter_title' => __('Sort','briefcase-elementor-widgets')
                    ],
                    [
                        'filter_type' => 'price',
                        'filter_title' => __('Price Filter','briefcase-elementor-widgets')
                    ],
                    [
                        'filter_type' => 'categories',
                        'filter_title' => __('Categories','briefcase-elementor-widgets')
                    ],
                ],
                'title_field' => '{{{ filter_title }}}',
				'condition' => [
					'shop_filter' => 'yes',
					'toolbar_show' => 'yes',
				],
            ]
        );

		$this->add_control(
			'sort',
			[
				'label' => __( 'Sort', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'toolbar_show' => 'yes',
					'shop_filter' => '',
				],
			]
		);
			
		$this->add_control(
			'paginate',
			[
				'label' => __( 'Pagination', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'toolbar_show' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'paginate_prev_next_style',
			[
				'label' => __( 'Prev/Next Style', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'text' => [
						'title' => __( 'Text', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-font',
					],
					'icon' => [
						'title' => __( 'Icon', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-chevron-right',
					],					
				],
				'default' => 'text',
				'prefix_class' => 'pagination-',	
				'condition' => [
                    'toolbar_show' => 'yes',
                ]
			]
		);
		
		$this->end_controls_section();
		}

		$this->start_controls_section(
            'section_filter',
            [
                'label' => __( 'Filter by Categories', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'grid_type' => [ 'shop', 'featured', 'latest'],					
                ]
            ]
        );	
		
		$this->add_control(
			'filter_show_cat',
			[
				'label' 		=> __( 'Filter by Category ', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'filter_show_cat_notice',
			[
				'raw' => __( 'IMPORTANT: Filter by Categories works on Shop Page grid style with display all the elements setting', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'by_cat_layout',
			[
				'label' => __( 'Layout', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => __( 'Horizontal', 'briefcase-elementor-widgets' ),
					'vertical' => __( 'Vertical', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'hide_empty_filter',
			[
				'label' => __( 'Hide Empty', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Hide',
				'label_off' => 'Show',
				'return_value' => '1',
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'parent_filter',
			[
				'label' => __( 'Only Top Level', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'yes',
				'label_off' => 'no',
				'return_value' => '0',
				'description' 	=> __( 'Show the top level categories on the filter', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'all_products_text',
			[
				'label' => __( 'All Product Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,				
				'default' => 'All Products',
				'placeholder' => __( 'All Products', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
				
		$this->add_responsive_control(
			'filter_align',
			[
				'label' => __( 'Alignment', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .filter-center' => 'text-align: {{VALUE}};',
				],
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
		$this->end_controls_section();	
		
		$this->start_controls_section(
			'section_filter_style',
			[
				'label' 		=> __( 'Filter by Categories', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'filter_show_cat' => 'yes',
					'grid_type' => [ 'shop', 'featured', 'latest'],					
                ]
			]
		);
				
		$this->add_responsive_control(
			'filter_category_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',				
				],
				'size_units' => [ '%' , 'px'],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid-filter .filter-center' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'filter_typo',
				'selector' 		=> '{{WRAPPER}} .filter-center',				
			]
		);
		
		$this->start_controls_tabs( 'tabs_filter_style' );
		
		$this->start_controls_tab(
			'tab_filter_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'filter_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'filter_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_filter_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'filter_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'filter_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'filter_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .product-filter li a:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
			

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_filter_active',
			[
				'label' => __( 'Active', 'elementor' ),
			]
		);
		
		$this->add_control(
			'filter_color_active',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a.active' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'filter_background_color_active',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a.active' => 'background-color: {{VALUE}};',
				],
				
			]
		);
			
		$this->add_control(
			'filter_active_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .product-filter li a.active' => 'border-color: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_control(
			'filter_color_arrow',
			[
				'label' 		=> __( 'Arrow color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-filter li a:hover, {{WRAPPER}} .product-filter li a.active' => '-webkit-box-shadow: 0 -3px 0 0 {{VALUE}} inset;',
					'{{WRAPPER}} .product-filter li a.active:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'filter_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .product-filter li a',		
				
			]
		);
				
		$this->add_control(
			'filter_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-filter li:first-child a' => 'border-radius: {{TOP}}{{UNIT}} 0{{UNIT}} 0{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .product-filter li:last-child a' => 'border-radius: 0{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .product-filter',
			]
		);
				
				
		$this->add_responsive_control(
			'filter_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-filter li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'filter_margin',
			[
				'label' => __( 'Filter Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .filter-center' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_toolbar_pagination',
			[
				'label' 		=> __( 'Toolbar and Pagination', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'filter_show_cat' => '',
					'grid_type' => 'shop',
					'toolbar_show' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'heading_toolbar',
			[
				'label' => __( 'Toolbar', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'toolbar_typo',
				'selector' 		=> '{{WRAPPER}} .bew-toolbar .bew-toolbar-head .woocommerce-result-count',				
			]
		);
		
		$this->add_control(
			'toolbar_count_color',
			[
				'label' 		=> __( 'Count Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .bew-toolbar-head .woocommerce-result-count' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'toolbar_sort_color',
			[
				'label' 		=> __( 'Sort Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .shop-filter .woocommerce-ordering .orderby' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sort_background_color',
			[
				'label' 		=> __( 'Sort Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .shop-filter .woocommerce-ordering .orderby' => 'background: {{VALUE}};',
				],
				
			]
		);
				
		$this->add_responsive_control(
			'toolbar_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .bew-toolbar-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'toolbar_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .bew-toolbar-head' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
						
		$this->add_control(
			'heading_pagination',
			[
				'label' => __( 'Pagination', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'pagination_typo',
				'selector' 		=> '{{WRAPPER}} .bew-woo-grid .woocommerce-pagination ul li a ,{{WRAPPER}} .bew-woo-grid .woocommerce-pagination ul li span',				
			]
		);
		
		$this->start_controls_tabs( 'tabs_pagination_style' );
		
		$this->start_controls_tab(
			'tab_pagination_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'pagination_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a, {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:before' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'pagination_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'pagination_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:hover, {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:hover:before,  {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:focus:before ' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'pagination_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:hover , {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:focus' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'pagination_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'pagination_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
			

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_pagination_active',
			[
				'label' => __( 'Active', 'elementor' ),
			]
		);
		
		$this->add_control(
			'pagination_color_active',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li span.current' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'pagination_background_color_active',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li span.current' => 'background-color: {{VALUE}};',
				],
				
			]
		);
			
		$this->add_control(
			'pagination_active_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'pagination_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li span.current' => 'border-color: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'pagination_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a, {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li span',		
				
			]
		);
		
		$this->add_control(
			'pagination_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li a, {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);	
		
		$this->add_control(
			'pagination_next_prev_size',
			[
				'label' => __( 'Next/Prev Icon Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 18,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination .next:before, {{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination .prev:before' => 'font-size: {{SIZE}}{{UNIT}}',
				],				
			]
		);
				
		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'pagination_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_responsive_control(
			'pagination_align',
			[
				'label' => __( 'Alignment', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .woocommerce nav.woocommerce-pagination ul' => 'text-align: {{VALUE}};',
				],				
			]
		);
		
		$this->end_controls_section();
		
	if (is_shop() || is_product_category() || is_product_tag() || $template_type == 'product-archive' ) {
		
		$this->start_controls_section(
			'section_shop_filter_style',
			[
				'label' 		=> __( 'Shop Filter', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'shop_filter' => 'yes',
					'grid_type' => [ 'shop', 'featured', 'latest'],
					'toolbar_show' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'heading_shop_filter_button',
			[
				'label' => __( 'Filter Button', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'shop_filter_typo',
				'selector' 		=> '{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters',				
			]
		);
		
		$this->start_controls_tabs( 'tabs_shop_filter' );
		
		$this->start_controls_tab(
			'tab_shop_filter_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'shop_filter_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_shop_filter_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'shop_filter_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters:hover, {{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters.opened ' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters:hover, {{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters.opened' => 'background-color: {{VALUE}};',
				],
				
			]
		);
			
		$this->add_control(
			'shop_filter_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'shop_filter_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'shop_filter_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters',		
				
			]
		);
		
		$this->add_control(
			'shop_filter_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',					
				],
				
			]
		);
		
		$this->add_responsive_control(
			'shop_filter_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons .open-filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
		
		$this->add_responsive_control(
			'shop_filter_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .bew-filter-buttons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_control(
			'heading_shop_filter_content',
			[
				'label' => __( 'Filters Content', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
				
		$this->add_control(
			'shop_filter_content_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .filters-area' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'shop_filter_content_titles_typo',
				'label' => __( 'Titles Typography', 'briefcase-elementor-widgets' ),
				'selector' 		=> '{{WRAPPER}} .bew-toolbar .filters-area .filter-title',				
			]
		);
				
		$this->add_control(
			'shop_filter_content_titles_color',
			[
				'label' 		=> __( 'Titles Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .filters-area .filter-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'shop_filter_content_typo',
				'label' => __( 'Content Typography', 'briefcase-elementor-widgets' ),
				'selector' 		=> '{{WRAPPER}} .bew-toolbar .filters-area .filter-list a',				
			]
		);
		
		$this->start_controls_tabs( 'tabs_shop_filter_content' );
		
		$this->start_controls_tab(
			'tab_shop_filter_content_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'shop_filter_content_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .filters-area .filter-list a, {{WRAPPER}} .bew-toolbar .filters-area .filter-list a .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_content_li_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .filters-area .filter-list li' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_shop_filter_content_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'shop_filter_content_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .filters-area .filter-list a:hover, {{WRAPPER}} .bew-toolbar .filters-area .filter-list a:hover .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_content_li_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-toolbar .filters-area .filter-list li:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		
		$this->add_responsive_control(
			'shop_filter_content_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .filters-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
		
		$this->add_responsive_control(
			'shop_filter_content_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-toolbar .filters-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_control(
			'shop_filter_search_box',
			[
				'label' => __( 'Search Box', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'shop_filter_search_box_typo',
				'selector' 		=> '{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input',				
			]
		);
		
		$this->add_control(
			'shop_filter_search_box_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_search_box_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input' => 'background: {{VALUE}};',
				],				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'shop_filter_search_box_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input',		
				
			]
		);
		
		$this->add_control(
			'shop_filter_search_box_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',					
				],
				
			]
		);
		
		$this->add_responsive_control(
			'shop_filter_search_box_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
		
		$this->add_responsive_control(
			'shop_filter_search_box_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew_categories_filter-list input, {{WRAPPER}} .bew-layered-nav-filter-list input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
				
		$this->add_control(
			'heading_shop_filter_bew_layered_nav',
			[
				'label' => __( 'Filter by Attributes', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'filter_bew_layered_nav_typo',
				'selector' 		=> '{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch a',				
			]
		);
		
		$this->start_controls_tabs( 'tabs_shop_filter_bew_layered_nav' );
		
		$this->start_controls_tab(
			'tab_shop_filter_bew_layered_nav_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch a' => 'background: {{VALUE}};',
				],				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_shop_filter_bew_layered_nav_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch:hover a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch:hover a' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'shop_filter_bew_layered_nav_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'shop_filter_bew_layered_nav_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch a:hover' => 'border-color: {{VALUE}};',
				],				
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'shop_filter_bew_layered_nav_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch a',		
				
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',					
				],
				
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_count_show',
			[
				'label' 		=> __( 'Show Count', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'show-count-filter-layered-nav-',
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_count_color',
			[
				'label' 		=> __( 'Text Color Count ', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch span.count' => 'color: {{VALUE}};',
				],
				'condition' => [
                    'shop_filter_bew_layered_nav_count_show' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'shop_filter_bew_layered_nav_count_background_color',
			[
				'label' 		=> __( 'Background Color Count', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-layered-nav-filter ul.show-labels-on li.no-swatch span.count' => 'background: {{VALUE}};',
				],
				'condition' => [
                    'shop_filter_bew_layered_nav_count_show' => 'yes',
                ]
				
			]
		);
		
		$this->add_control(
			'heading_shop_filter_categories',
			[
				'label' => __( 'Filter by Categories', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'shop_filter_categories_show',
			[
				'label' 		=> __( 'Show Count', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'show-count-filter-categories-',
			]
		);
		
		$this->add_control(
			'shop_filter_categories_color',
			[
				'label' 		=> __( 'Text Color Count ', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew_categories_filter .widget_product_categories .count' => 'color: {{VALUE}};',
				],
				'condition' => [
                    'shop_filter_categories_show' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'shop_filter_categories_background_color',
			[
				'label' 		=> __( 'Background Color Count', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew_categories_filter .widget_product_categories .count' => 'background: {{VALUE}};',
				],
				'condition' => [
                    'shop_filter_categories_show' => 'yes',
                ]
				
			]
		);
		
        $this->end_controls_section();		
		
		}
			
		$this->start_controls_section(
			'section_grid',
			[
				'label' 		=> __( 'Grid', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'grid_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],				
				'size_units' => [ '%' , 'px'],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid-filter .products-items' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);

		$this->add_control(
			'grid_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .product .elementor-inner, {{WRAPPER}} .bew-grid-categories .bew-product-category .elementor-inner' => 'background-color: {{VALUE}};',
				],
			]
		);
				
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'grid_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-woo-grid .product .elementor-inner, {{WRAPPER}} .bew-grid-categories .bew-product-category .elementor-inner',		
				
			]
		);
		
		$this->add_control(
			'bew_grid_border_color',
			[
				'label' 		=> __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-grid .product .elementor-inner, {{WRAPPER}} .bew-grid-categories .bew-product-category .elementor-inner' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'grid_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .product .elementor-inner, {{WRAPPER}} .bew-grid-categories .bew-product-category .elementor-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					
				],
				
			]
		);
		
		$this->add_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .bew-grid, {{WRAPPER}} .bew-woo-grid.bew-shop-shortcode .products' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
				'condition' => [
                    'filter_show_cat' => '',
                ]
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .bew-grid, {{WRAPPER}} .bew-woo-grid.bew-shop-shortcode .products' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
                    'filter_show_cat' => '',
                ]
			]
		);
				
		$this->add_responsive_control(
			'grid_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .product, {{WRAPPER}} .bew-grid-categories .product-category, {{WRAPPER}} .bew-woo-grid-filter .products-items .products-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'grid_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-grid .product, {{WRAPPER}} .bew-grid-categories .product-category, {{WRAPPER}} .bew-woo-grid-filter .products-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'filter_show_cat' => 'yes',
                ]
			]
		); 	


        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_woo_grid_navigation',
			[
				'label'     => __( 'Slider Navigation', 'briefcase-elementor-widgets' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [					
					'woo_grid_layout' => 'slider',					
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label'     => __( 'Arrows', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label'        => __( 'Position', 'briefcase-elementor-widgets' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'outside',
				'options'      => [
					'inside'  => __( 'Inside', 'briefcase-elementor-widgets' ),
					'outside' => __( 'Outside', 'briefcase-elementor-widgets' ),
				],
				'prefix_class' => 'woo-grid-slider-arrow-',
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrows_style',
			[
				'label'        => __( 'Style', 'briefcase-elementor-widgets' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => '',
				'options'      => [
					''       => __( 'Default', 'briefcase-elementor-widgets' ),
					'circle' => __( 'Circle', 'briefcase-elementor-widgets' ),
					'square' => __( 'Square', 'briefcase-elementor-widgets' ),
				],
				'prefix_class' => 'woo-grid-slider-arrow-',
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'arrow_tabs_style' );
			$this->start_controls_tab(
				'arrow_style_normal',
				[
					'label'     => __( 'Normal', 'briefcase-elementor-widgets' ),
					'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],
				]
			);
			
			$this->add_control(
				'arrows_color',
				[
					'label'     => __( 'Color', 'briefcase-elementor-widgets' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bew-products-slider button.slick-arrow' => 'color: {{VALUE}};',
					],
					'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
					],
				]
			);
			
			$this->add_control(
				'arrows_bg_color',
				[
					'label'     => __( 'Background Color', 'briefcase-elementor-widgets' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bew-products-slider button.slick-arrow' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'woo_grid_layout' => 'slider',
						'woo_grid_slider_navigation_arrows' => 'yes',
						'arrows_style'         => [ 'circle', 'square' ],
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'arrow_style_hover',
				[
					'label'     => __( 'Hover', 'briefcase-elementor-widgets' ),
					'condition' => [
						'woo_grid_layout' => 'slider',
						'woo_grid_slider_navigation_arrows' => 'yes',
					],
				]
			);
			
			$this->add_control(
					'arrows_hover_color',
					[
						'label'     => __( 'Hover Color', 'briefcase-elementor-widgets' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bew-products-slider button.slick-arrow:hover' => 'color: {{VALUE}};',
						],
						'condition' => [
						'woo_grid_layout' => 'slider',
						'woo_grid_slider_navigation_arrows' => 'yes',
						],
					]
				);
				
			$this->add_control(
					'arrows_hover_bg_color',
					[
						'label'     => __( 'Background Hover Color', 'briefcase-elementor-widgets' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .bew-products-slider button.slick-arrow:hover' => 'background-color: {{VALUE}};',
						],
						'condition' => [
							'woo_grid_layout' => 'slider',
							'woo_grid_slider_navigation_arrows' => 'yes',
							'arrows_style'         => [ 'circle', 'square' ],
						],
					]
				);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		
		$this->add_control(
			'arrows_size',
			[
				'label'     => __( 'Size', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider button.slick-arrow:before, {{WRAPPER}} .bew-products-slider button.slick-arrow:hover:before , {{WRAPPER}} .bew-products-slider button.slick-arrow:focus:before' => 'font-size: {{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'arrows_bg_width',
			[
				'label' => __( 'Background Width', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 120,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider button.slick-arrow' => 'width:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],			
			]
		);

		$this->add_responsive_control(
			'arrows_bg_height',
			[
				'label' => __( 'Background Height', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 120,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider button.slick-arrow' => 'height:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_arrows' => 'yes',
				],			
			]
		);
		
		$this->add_responsive_control(
			'arrows_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider button.slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'arrows_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider button.slick-arrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label'     => __( 'Dots', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
							'woo_grid_layout' => 'slider',
							'woo_grid_slider_navigation_dots' => 'yes',				
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label'     => __( 'Size', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
							'woo_grid_layout' => 'slider',
							'woo_grid_slider_navigation_dots' => 'yes',				
				],
			]
		);
		
		$this->add_control(
			'dots_size_active',
			[
				'label'     => __( 'Active Size', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider .slick-dots li.slick-active button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
							'woo_grid_layout' => 'slider',
							'woo_grid_slider_navigation_dots' => 'yes',				
				],
			]
		);
		
		$this->start_controls_tabs( 'dots_tabs_style' );
		$this->start_controls_tab(
				'dots_style_normal',
				[
					'label'     => __( 'Normal', 'briefcase-elementor-widgets' ),
					'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_dots' => 'yes',
				],
				]
			);

		$this->add_control(
			'dots_color',
			[
				'label'     => __( 'Color', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider .slick-dots li button' => 'background-color: {{VALUE}};',
				],
				'condition' => [
							'woo_grid_layout' => 'slider',
							'woo_grid_slider_navigation_dots' => 'yes',				
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'dots_style_active',
			[
				'label'     => __( 'Active', 'briefcase-elementor-widgets' ),
				'condition' => [
					'woo_grid_layout' => 'slider',
					'woo_grid_slider_navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'dots_color_active',
			[
				'label'     => __( 'Color', 'briefcase-elementor-widgets' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-products-slider .slick-dots li.slick-active button ' => 'background-color: {{VALUE}};',
				],
				'condition' => [
							'woo_grid_layout' => 'slider',
							'woo_grid_slider_navigation_dots' => 'yes',				
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		
		}
	}

	public function get_current_page() {
		return max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
	}

	public function get_img_sizes() {
		global $_wp_additional_image_sizes;

		$sizes = array();
	    $get_intermediate_image_sizes = get_intermediate_image_sizes();
	 
	    // Create the full array with sizes and crop info
	    foreach( $get_intermediate_image_sizes as $_size ) {
	        if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
	            $sizes[ $_size ]['width'] 	= get_option( $_size . '_size_w' );
	            $sizes[ $_size ]['height'] 	= get_option( $_size . '_size_h' );
	            $sizes[ $_size ]['crop'] 	= (bool) get_option( $_size . '_crop' );
	        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
	            $sizes[ $_size ] = array( 
	                'width' 	=> $_wp_additional_image_sizes[ $_size ]['width'],
	                'height' 	=> $_wp_additional_image_sizes[ $_size ]['height'],
	                'crop' 		=> $_wp_additional_image_sizes[ $_size ]['crop'],
	            );
	        }
	    }

	    $image_sizes = array();

		foreach ( $sizes as $size_key => $size_attributes ) {
			$image_sizes[ $size_key ] = ucwords( str_replace( '_', ' ', $size_key ) ) . sprintf( ' - %d x %d', $size_attributes['width'], $size_attributes['height'] );
		}

		$image_sizes['full'] 	= _x( 'Full', 'Image Size Control', 'briefcase-portfolio' );

	    return $image_sizes;
	}
	
	public function get_edit_buttom() {
		
	$settings = $this->get_settings();
		
	$template_id = $this->get_settings( 'template_id' );
	
	}
		
	private function get_shortcode() {
		$settings = $this->get_settings();
					
		$template_id = $this->get_settings( 'template_id' );
		
		$attributes = [
			'number' => $settings['count'],
			'columns' => $settings['columns'],
			'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,
			'orderby' => $settings['orderby_cat'],
			'order' => $settings['order'],
		];

		if ( 'by_id' === $settings['source'] ) {
			$attributes['ids'] = implode( ',', $settings['categories'] );
		} elseif ( 'by_parent' === $settings['source'] ) {
			$attributes['parent'] = $settings['parent'];
		}

		$this->add_render_attribute( 'shortcode', $attributes );
			
		$shortcode = sprintf( '[product_categories %s]', $this->get_render_attribute_string( 'shortcode' ) );
		
		return $shortcode;
	}
			
	private function bew_product_categories($template_id) {
		
		$settings = $this->get_settings();
		 		
		// Get terms and workaround WP bug with parents/pad counts.
		$args = array(
			'orderby'    => $settings['orderby'],
			'order'      => $settings['order'],
			'number'     => '',
			'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,			
			'pad_counts' => true,			
		);
		
		if ( 'by_id' === $settings['source'] ) {
			$args['include'] = implode( ',', $settings['categories'] );
		} elseif ( 'by_parent' === $settings['source'] ) {
			$args['parent'] = $settings['parent'];
		}

		$product_categories = get_terms( 'product_cat', $args );
		
		$columns = absint( $settings['columns'] );

		if ( $product_categories ) {
			echo '<div class="bew-grid-categories bew-grid">';	
			
				foreach ( $product_categories as $category ) {				
				
				global $bewcategory;		
				$bewcategory = $category;	
			
				// Inner classes					
					$inner_classes = array( 'bew-product-category' );		
					$inner_classes = implode( ' ', $inner_classes );
					
					// Get the current template	
					echo '<div class="'. esc_attr( $inner_classes ) . '">';					
						$withcss =false;
						if(Elementor\Plugin::instance()->editor->is_edit_mode()){
						$withcss = true;
						}												
						echo Elementor\Plugin::instance()->frontend->get_builder_content( $template_id,$withcss );					
					echo '</div>';					
				}			
			echo '</div>';		
		}				
	}
		
	function prefix_post_class( $classes ) {
    if ( 'product' == get_post_type() ) {
        $classes = array_diff( $classes, array( 'first', 'last' ) );
    }
    return $classes;
	}
		
		
	protected function get_products_query_args(){
        $helper = new Helper();
		$settings = $this->get_settings();
		
		$grid_type = $this->get_settings( 'grid_type' );
		$count_page = $settings['count'];
						
        switch($grid_type){
			
			case 'shop':
						// Array for shop page layout
						$args = array(
							'post_type'         => 'product',
							'paged' 			=> $this->get_current_page(),
							'posts_per_page'    => $settings['count'],
							'order'             => $settings['order'],
							'orderby'           => $settings['orderby'],
							'no_found_rows' 	=> false,
							'tax_query' 		=> array(
								'relation' 		=> 'AND',
							),
						);
						
						if (is_shop() or is_product_category()) {
							// Order by drop down on shop page 
								$ordering_args = WC()->query->get_catalog_ordering_args( $query_args['orderby'], $query_args['order'] );
									$args['orderby'] = $ordering_args['orderby'];
									$args['order'] = $ordering_args['order'];
									if ( $ordering_args['meta_key'] ) {
										$args['meta_key'] = $ordering_args['meta_key'];
							}
							
							// Paged
							global $paged;
							
							if ( get_query_var( 'paged' ) ) {
								$paged = get_query_var( 'paged' );
							} else if ( get_query_var( 'page' ) ) {
								$paged = get_query_var( 'page' );
							} else {
								$paged = 1;
							}

							if ( 1 < $paged ) {
								$args['paged'] = $paged;
							}
						
						}
						// Include/Exclude categories
						$include = $settings['include_categories'];
						$exclude = $settings['exclude_categories'];
						
						// Include category
						if ( ! empty( $include ) ) {
					
							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $include,
								'include_children' => false,
								'operator' => 'IN',
							);
						}

						// Exclude category
						if ( ! empty( $exclude ) ) {

							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $exclude,								
								'operator' => 'NOT IN',
							);
							
						}
						
						
						
			break;
					
			case 'latest':
						// Array for shop page layout
						$args = array(
							'post_type'         => 'product',
							'paged' 			=> $this->get_current_page(),
							'posts_per_page'    => $settings['count'],
							'order'             => $settings['order'],
							'orderby'           => $settings['orderby'],
							'no_found_rows' 	=> true,
							'tax_query' 		=> array(
								'relation' 		=> 'AND',
							),
						);
																		
						// Include/Exclude categories
						$include = $settings['include_categories'];
						$include_children = false;
						
						$exclude = $settings['exclude_categories'];
						
						// Include category
						if ( ! empty( $include ) ) {
					
							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $include,
								'include_children' => $include_children,
								'operator' => 'IN',
							);
						}

						// Exclude category
						if ( ! empty( $exclude ) ) {

							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $exclude,								
								'operator' => 'NOT IN',
							);
							
						}
			break;		
					
			case 'featured':
						// Array for featured products
						$args = array(
							'post_type'         => 'product',
							'paged' 			=> $this->get_current_page(),
							'posts_per_page'    => $settings['count'],							
							'order'             => $settings['order'],
							'orderby'           => $settings['orderby'],
							'no_found_rows' 	=> true,
							'tax_query' 		=> array(
								'relation' 		=> 'AND',
							),
						);

						// Include/Exclude categories
						$include = $settings['include_categories'];
						$exclude = $settings['exclude_categories'];
						
						// Include featured
											
							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_visibility',
								'field'    => 'name',
								'terms'    => 'featured',								
							);
						
						
						// Include category
						if ( ! empty( $include ) ) {
					
							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $include,
								'include_children' => false,
								'operator' => 'IN',
							);
						}

						// Exclude category
						if ( ! empty( $exclude ) ) {

							// Add to query arg
							$args['tax_query'][] = array(
								'taxonomy' => 'product_cat',
								'field'    => 'id',
								'terms'    => $exclude,								
								'operator' => 'NOT IN',
							);
							
						}
			break;		
			
            case 'related' : $product = $helper->product_data();
								
                             if(is_null($product) || $product->post_type != 'product'){
                                 return [];
                             }
                             $related_products = wc_get_related_products($product->get_id(), $count_page, $product->get_upsell_ids());
                             $args = [
                                 'post_type' => 'product',
								 'posts_per_page' => $count_page,
                                 'stock' => 1,
                                 'orderby' =>'date',
                                 'order' => 'DESC',
                                 'post__in' => $related_products
                             ];
            break;

            case 'upsell'   : $product = $helper->product_data();
                              if(is_null($product) || $product->post_type != 'product'){
                                  return [];
                              }
                              $upsell_products = $product->get_upsell_ids();
                                $args = [
                                    'post_type' => 'product',
                                    'posts_per_page' => $count_page,
                                    'stock' => 1,
                                    'orderby' =>'date',
                                    'order' => 'DESC',
                                    'post__in' => $upsell_products
                                ];
            break;			
        }
        return $args;
    }
	
	protected function layout_filter(){
		
		$settings = $this->get_settings();
				
		// Include/Exclude categories on filter
				$includef = $settings['include_categories'];
				$excludef = $settings['exclude_categories'];
		// Categories List
			$args = array( 
				'hide_empty'   => $settings['hide_empty_filter'], 
				'order' 	   => $settings['order'],
				'pad_counts'   => true,
				'exclude'      => $excludef,
				'exclude_tree' => $excludef, 
				'include'      => $includef,
				'parent'       => $settings['parent_filter'],
			); 			
			
			$categories = get_terms( 'product_cat', $args );
				
			$all_products= $settings['all_products_text'];
		
		?>	
		<div class="filter-center">
			<ul class="product-filter">
				<li><a class="active" href="#" data-filter="*"><?php echo $all_products ?></a></li>
			<?php foreach ($categories as $cat) { ?>
				<li><a class="<?php echo sanitize_title($cat->name);?>" href="#" data-filter=".<?php echo sanitize_title($cat->name); ?>" ><?php echo $cat->name; ?></a></li>		
			<?php } ?>
			</ul><!--/#product-filter-->
		</div>
		<?php
		// Class to initiate isotope filter	
		?>
		<div class="products-items">
		<?php
	
	}
	
	protected function get_template_id(){
		$settings = $this->get_settings();
		
		$woo_grid_cache = get_option('woo_grid_cache');
		
		// Get the template Id		
		$grid_type = $this->get_settings( 'grid_type' );		
		$helper = new Helper();
				
		if('categories' == $grid_type  ){
			
			$template_slug = $settings['template_type_cat'];
			
			if ($woo_grid_cache == 1){
				$template_id = $helper->bew_get_templates_cat_id($template_slug);	
			}else{				
				$template_id = $helper->bew_get_templates_cat_id_no_cache($template_slug);
			}
			
		} else {
			
			$template_slug = $settings['template_type'];
			
			if ($woo_grid_cache == 1){
				$template_id = $helper->bew_get_templates_id($template_slug);	
			}else{
				$template_id = $helper->bew_get_templates_id_no_cache($template_slug);	
			}
			
		}
		return $template_id;
	}
	
	protected function apply_template($bew_query,$template_id,$template_id_verification){
		
		$settings = $this->get_settings();
				
		// Vars
			
			$equal_heights 	 = $settings['grid_equal_heights'];
			$show_filter 	 = $settings['filter_show_cat'];		
			$grid_style 	 = $settings['grid_style'];		
			$woo_grid_layout = $settings['woo_grid_layout'];
		
		if ($woo_grid_layout == 'slider'){
			$columns = $settings['woo_grid_slides_to_show'];	
		}else{
			$columns_desktop = $settings['columns']; 
			$columns_tablet = $settings['columns_tablet']; 
			$columns_mobile = $settings['columns_mobile'];
		}
				
		if($template_id_verification  == '1'){	 
			// Default template	for related products		
				if($template_id  == "0"){				
							
					$frontend = Frontend::instance();
					$check_template = $frontend->check_wc_shop_template();	
							
					$template_id = $check_template;						
							
				}
			
			// Define counter var to clear floats
				$count = '';
				$countp = '';
				
			// Start loop
					while ( $bew_query->have_posts() ) : $bew_query->the_post();
						
						if ( 'yes' == $show_filter ||  $woo_grid_layout == 'slider' ) {
						// Counter
						$count++;
						$countp++;
						}
						
						// If equal heights
						$details_class = '';
						if ( 'yes' == $equal_heights ) {
							$details_class = ' match-height-content';
						}
	
						// Create new post object.
							$post = new \stdClass();

						// Get post data
							$get_post = get_post();

						// Post Data
							$post->ID           = $get_post->ID;
							$post->permalink    = get_the_permalink( $post->ID );
							$post->title        = $get_post->post_title;

						// Only display grid item if there is content to show
						if ( has_post_thumbnail()) { 
								
						// Inner classes												
						
							if ( 'yes' == $show_filter ) {
								$inner_classes 		= array( 'products-item','bew-grid-entry', 'bew-col', 'clr');					
							} else {					
								if ($woo_grid_layout == 'slider') {
								$inner_classes 		= array( 'product-slider-item','bew-grid-entry', 'bew-col', 'clr');	
								}else{
								$inner_classes 		= array('');	
								}								
							}
							
							if ( 'yes' == $show_filter) {
							$inner_classes[] 	= 'c-' . $countp;
							$inner_classes[] 	= 'bew_span_1_of_' . $columns_desktop;
							$inner_classes[] 	= 'bew_span_1_of_tablet-' . $columns_tablet;
							$inner_classes[] 	= 'bew_span_1_of_mobile-' . $columns_mobile;
							$inner_classes[] 	= 'bew-col-' . $count;
							}
							
							if ($woo_grid_layout == 'slider') {
							$inner_classes[] 	= 'c-' . $countp;
							$inner_classes[] 	= 'bew_span_1_of_' . $columns;							
							}
							
							if ( 'masonry' == $grid_style ) {
								$inner_classes[] = 'isotope-entry';
							}
								
							$terms = get_the_terms ( $post->ID, 'product_cat' );
							
							foreach ( $terms as $term ) {
								$cat_name = sanitize_title($term->name);
								
								$inner_classes[] 	= $cat_name;
							}
													
							$inner_classes = implode( ' ', $inner_classes );
								
						// unset firts/last classes
							add_filter( 'post_class', array( $this, 'prefix_post_class' ), 21 );							
							
							
								// Get the current template	
								$withcss =false;
								if(Elementor\Plugin::instance()->editor->is_edit_mode()){
								$withcss = true;
								}
									
								?>	
								<div id="post-<?php the_ID(); ?>" <?php post_class( $inner_classes ); ?>>
								<?php		
													
									echo Elementor\Plugin::instance()->frontend->get_builder_content( $template_id,$withcss );
										
								?>
								</div>
								<?php
						} ?>

						<?php
					// Reset entry counter
						if ( $count == $columns_desktop ) {
								$count = '0';
						} ?>

						<?php
					// End entry loop
					endwhile;
					
			}else{
				?>
				<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'briefcase-elementor-widgets' ); ?></p>
				<?php
			}
	}
	
	protected function bew_shop_toolbar(){
		
		$settings = $this->get_settings();		
	
			// Bew Tool Bar
			?>
			<div class="bew-toolbar shop-loop-head row">
				<div class="shop-display col-xl-7 col-lg-6">
					<?php
					if('yes' == $settings['result_count']){
					 woocommerce_result_count(); 
					} 
					?>
				</div>
				<div class="shop-filter col-xl-5 col-lg-6">
					<?php
					
					if('yes' == $settings['sort']){
					woocommerce_catalog_ordering();
					}
					
					if('yes' == $settings['col_switcher']){

						$columns = apply_filters( 'bew_shop_products_columns',
							array(
								'xs' => 1,
								'sm' => 2,
								'md' => 3,
								'lg' => 3,
								'xl' => get_option( 'woocommerce_catalog_columns', 5 ),
							) );

							?>
						<div class="col-switcher"
						     data-cols="<?php echo esc_attr( json_encode( $columns ) ); ?>"><?php esc_html_e( 'See:',
								'briefcase-elementor-widgets' ); ?>
							<a href="#" data-col="2">2</a>
							<a href="#" data-col="3">3</a>
							<a href="#" data-col="4">4</a>
							<a href="#" data-col="5">5</a>
							<a href="#" data-col="6">6</a>
						</div><!-- .col-switcher -->
					<?php } ?>

					<?php if('yes' == $settings['shop_filter']){?>
							<div class="bew-filter-buttons">
								<a href="#" class="open-filters"><?php esc_html_e( 'Filters', 'briefcase-elementor-widgets' ); ?></a>
							</div><!-- .bew-filter-buttons -->
					<?php } ?>
				</div>
			</div><!--.shop-loop-head -->

			<?php if('yes' == $settings['shop_filter']){?>
				<div class="filters-area">
					<div class="filters-inner-area row">
						<?php dynamic_sidebar( 'filters-area' ); ?>
					</div><!-- .filters-inner-area -->
				</div><!--.filters-area-->

				<div
					class="active-filters"><?php the_widget( 'WC_Widget_Layered_Nav_Filters' ); ?></div><!--.active-filters-->
			<?php } ?>

			<?php
	
	
	}
	
	function bew_woocommerce_pagination($bew_query = '', $echo = true ) {
		// Arrows with RTL support
		$prev_arrow = is_rtl() ? 'fa fa-angle-right' : 'fa fa-angle-left';
		$next_arrow = is_rtl() ? 'fa fa-angle-left' : 'fa fa-angle-right';
		
		// Get global $query
		if ( ! $bew_query ) {
			global $wp_query;
			$bew_query = $wp_query;
		}

		// Set vars
		$total  = $bew_query->max_num_pages;		
		$big    = 999999999;
		
		// Display pagination if total var is greater then 1 ( current query is paginated )
		if ( $total > 1 ) {

			// Get current page
			if ( $current_page = get_query_var( 'paged' ) ) {
				$current_page = $current_page;
			} elseif ( $current_page = get_query_var( 'page' ) ) {
				$current_page = $current_page;
			} else {
				$current_page = 1;
			}

			// Get permalink structure
			if ( get_option( 'permalink_structure' ) ) {
				if ( is_page() ) {
					$format = 'page/%#%/';
				} else {
					$format = '/%#%/';
				}
			} else {
				$format = '&paged=%#%';
			}

			$args = apply_filters( 'bew_pagination_args', array(
				'base'      => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
				'format'    => $format,
				'current'   => max( 1, $current_page ),
				'total'     => $total,
				'mid_size'  => 3,
				'type'      => 'list',
				'prev_text' => '<i class="'. $prev_arrow .'"></i>',
				'next_text' => '<i class="'. $next_arrow .'"></i>',
			) );

			// Output pagination
			if ( $echo ) {
				echo '<div class="bew-pagination clr">'. wp_kses_post( paginate_links( $args ) ) .'</div>';
			} else {
				return '<div class="bew-pagination clr">'. wp_kses_post( paginate_links( $args ) ) .'</div>';
			}
		}
	}
	
	/**
	 * Get Wrapper Classes.
	 *
	 * @since 1.4.6
	 * @access public
	 */
	public function set_woo_grid_slider_attr() {

		$settings = $this->get_settings();
		
		$is_rtl      = is_rtl();
		$direction   = $is_rtl ? 'rtl' : 'ltr';
		$show_dots   = ( $settings['woo_grid_slider_navigation_dots'] == 'yes' ) ? true : false;
		$show_arrows = ( $settings['woo_grid_slider_navigation_arrows'] == 'yes' ) ? true : false;

		$slick_options = [
			'slidesToShow'   => ( $settings['woo_grid_slides_to_show'] ) ? absint( $settings['woo_grid_slides_to_show'] ) : 4,
			'slidesToScroll' => ( $settings['woo_grid_slides_to_scroll'] ) ? absint( $settings['woo_grid_slides_to_scroll'] ) : 1,
			'autoplaySpeed'  => ( $settings['woo_grid_slider_autoplay_speed'] ) ? absint( $settings['woo_grid_slider_autoplay_speed'] ) : 5000,
			'autoplay'       => ( 'yes' === $settings['woo_grid_slider_autoplay'] ),
			'infinite'       => ( 'yes' === $settings['woo_grid_slider_infinite'] ),
			'pauseOnHover'   => ( 'yes' === $settings['woo_grid_slider_pause_on_hover'] ),
			'speed'          => ( $settings['woo_grid_slider_transition_speed'] ) ? absint( $settings['woo_grid_slider_transition_speed'] ) : 500,
			'arrows'         => $show_arrows,
			'dots'           => $show_dots,
			'rtl'            => $is_rtl,
			'prevArrow'      => '<button type="button" data-role="none" class="slick-prev slick-arrow fa fa-angle-left" aria-label="Previous" role="button"></button>',
			'nextArrow'      => '<button type="button" data-role="none" class="slick-next slick-arrow fa fa-angle-right" aria-label="Next" role="button"></button>',
		];

		if ( $settings['woo_grid_slides_to_show_tablet'] || $settings['woo_grid_slides_to_show_mobile'] ) {

			$slick_options['responsive'] = [];

			if ( $settings['woo_grid_slides_to_show_tablet'] ) {

				$tablet_show   = absint( $settings['woo_grid_slides_to_show_tablet'] );
				$tablet_scroll = ( $settings['woo_grid_slides_to_scroll_tablet'] ) ? absint( $settings['woo_grid_slides_to_scroll_tablet'] ) : $tablet_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 1024,
					'settings'   => [
						'slidesToShow'   => $tablet_show,
						'slidesToScroll' => $tablet_scroll,
					],
				];
			}

			if ( $settings['woo_grid_slides_to_show_mobile'] ) {

				$mobile_show   = absint( $settings['woo_grid_slides_to_show_mobile'] );
				$mobile_scroll = ( $settings['woo_grid_slides_to_scroll_mobile'] ) ? absint( $settings['woo_grid_slides_to_scroll_mobile'] ) : $mobile_show;

				$slick_options['responsive'][] = [
					'breakpoint' => 767,
					'settings'   => [
						'slidesToShow'   => $mobile_show,
						'slidesToScroll' => $mobile_scroll,
					],
				];
			}
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'data-woo_grid_slider' => wp_json_encode( $slick_options ),
			]
		);
	}
		
	protected function render() {
		$settings = $this->get_settings();
		$id_element = $this->get_id();
		
		$woo_grid_layout 	= $settings['woo_grid_layout'];
		$columns_gap 		= $settings['column_gap'];
		$columns_gap        = $columns_gap['size'];
		$columns 		    = $settings['columns'];
					
		$helper = new Helper();
		if($helper->is_briefcasewp_extras_installed()){			
			$template_id = $this->get_template_id();			
		}else{
			$template_id = $this->get_settings( 'template_id' );	
		}
		
		$grid_style 	= $settings['grid_style'];
		$equal_heights 	= $settings['grid_equal_heights'];
		
		$grid_type 		= $this->get_settings( 'grid_type' );
		if(isset($settings['toolbar_show'])) {
		$show_toolbar 	= $settings['toolbar_show'];
		}else{
		$show_toolbar 	= '';	
		}
		$show_filter 	= $settings['filter_show_cat'];
		$vertical_filter 	= $settings['by_cat_layout'];
		
		$bew_woo_grid_loader   = '';
				
		 switch($grid_type){
			
			case 'shop':
			case 'latest':
				if ('shop' == $grid_type) {
					
					if ((is_woocommerce()) && $settings['count'] != '-1'  ) {
						
						//Grid for Shop
						
						if ( WC()->session ) {
						wc_print_notices();
						}

						// For Products_Renderer.
						if ( ! isset( $GLOBALS['post'] ) ) {
							$GLOBALS['post'] = null; // WPCS: override ok.
						}

						$settings = $this->get_settings();
						
						$shortcode = new Products_Renderer( $settings, 'products' , $template_id  );

						$content = $shortcode->bew_get_content();

						if ( $content ) {
							// Wrapper classes
							$wrap_classes = array( 'bew-woo-grid bew-shop-shortcode');
							$wrap_classes = implode( ' ', $wrap_classes ); 
							
							// Layout Shop Page
							?>					
							<div class="<?php echo esc_attr( $wrap_classes ); ?>" id="<?php echo $template_id ; ?>" data-columns= "<?php echo $columns; ?>" data-columns-gap = "<?php echo $columns_gap; ?>">							
							<?php if ( $bew_woo_grid_loader == "yes" ) { ?>	
								<div id="woo-grid-loader">
									<div class="bew-loader-content">
									<div id="bew-animates">
									<div class="spinner"></div>
									</div>
									</div>
								</div>
							<?php } ?>	
							<?php
							echo $content;
							?>
							</div>							
							<?php 
						} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
							echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
						}
						
					} else {
												
						//Grid for any page 
							
						$templates = $this->get_templates();
						$template_id_verification = '0';
							
						foreach ( $templates as $template ) {
							if ($template['template_id'] == $template_id ){
								$template_id_verification = '1';
							}  ;			
						}		
						
						// Get args for query
						$args = $this->get_products_query_args();		
								
						// Build the WordPress query
						$bew_query = new \WP_Query( $args );
									
						// Output posts
						if ( $bew_query->have_posts() ) :
												
							// Wrapper classes
														
							if ( 'yes' == $show_filter) {								
								$wrap_classes = array( 'bew-woo-grid-filter' ,'briefcasewp-row', 'clr' );
									if ( 'vertical' == $vertical_filter) {
									$wrap_classes[] = 'bew-woo-grid-filter-vertical';	
									}
							}else{
								if ($woo_grid_layout == 'slider'){
									$wrap_classes = array('bew-woo-grid-slider', 'woocommerce', 'briefcasewp-row', 'clr' );	
								}else{
									$wrap_classes = array( 'bew-woo-grid', 'woocommerce');	
								}
								
							}
							
							if ( 'masonry' == $grid_style ) {
								$wrap_classes[] = 'bew-masonry';
							}
							
							if ( 'yes' == $equal_heights ) {
								$wrap_classes[] = 'match-height-grid';
							}

							$wrap_classes = implode( ' ', $wrap_classes ); 
							
							// Woo Grid Slider
							if ($woo_grid_layout == 'slider'){			
							$this->set_woo_grid_slider_attr();		
							}
									
							// Layout Shop Page
							?>					
							<div class="<?php echo esc_attr( $wrap_classes ); ?>" id="<?php echo $template_id ; ?>">
							<?php
							
							// Shop Toolbar option
							if ( 'yes' == $show_toolbar) {
								$this->bew_shop_toolbar();						 
							} 
									
							// Filter option
							if ( 'yes' == $show_filter) {
								$this->layout_filter();						 
							}else {					
								if ($woo_grid_layout == 'slider'){
									?>
									<div class="products bew-products-slider" <?php echo  $this->get_render_attribute_string( 'wrapper' ); ?>>						
									<?php
								}else{
									?>
									<div class="bew-products bew-grid">								
									<?php
								}					
															
							}									
									// Apply template module
										$this->apply_template($bew_query,$template_id,$template_id_verification);
									?>	
									</div>
							</div>								
							<?php 
						
						// Reset the post data to prevent conflicts with WP globals
						wp_reset_postdata();

						// If no posts are found display message
						else : ?>

							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'briefcase-elementor-widgets' ); ?></p>

						<?php
						// End post check
						endif;
						
					}
						
				} elseif ('latest' == $grid_type) {
						
						//Grid for any page 
							
						$templates = $this->get_templates();
						$template_id_verification = '0';
							
						foreach ( $templates as $template ) {
							if ($template['template_id'] == $template_id ){
								$template_id_verification = '1';
							}  ;			
						}
						
						// Get args for query
						$args = $this->get_products_query_args();		
								
						// Build the WordPress query
						$bew_query = new \WP_Query( $args );
									
						// Output posts
						if ( $bew_query->have_posts() ) :
							
							// Wrapper classes
														
							if ( 'yes' == $show_filter) {
								$wrap_classes = array( 'bew-woo-grid-filter' ,'briefcasewp-row', 'clr' );
							}else{
								if ($woo_grid_layout == 'slider'){
									$wrap_classes = array('bew-woo-grid-slider', 'woocommerce', 'briefcasewp-row', 'clr' );	
								}else{
									$wrap_classes = array( 'bew-woo-grid', 'woocommerce');	
								}
								
							}
							
							if ( 'masonry' == $grid_style ) {
								$wrap_classes[] = 'bew-masonry';
							}
							
							if ( 'yes' == $equal_heights ) {
								$wrap_classes[] = 'match-height-grid';
							}

							$wrap_classes = implode( ' ', $wrap_classes ); 
							
							// Woo Grid Slider
							if ($woo_grid_layout == 'slider'){			
							$this->set_woo_grid_slider_attr();		
							}
									
							// Layout Shop Page
							?>					
							<div class="<?php echo esc_attr( $wrap_classes ); ?>" id="<?php echo $template_id ; ?>">
							<?php
							
							// Shop Toolbar option
							if ( 'yes' == $show_toolbar) {
								//$this->bew_shop_toolbar();						 
							} 
									
							// Filter option
							if ( 'yes' == $show_filter) {
								$this->layout_filter();						 
							}else {					
								if ($woo_grid_layout == 'slider'){
									?>
									<div class="products bew-latest-products bew-products-slider" <?php echo  $this->get_render_attribute_string( 'wrapper' ); ?>>						
									<?php
								}else{
									?>
									<div class="bew-products bew-latest-products  bew-grid">								
									<?php
								}							
							}
																
							// Apply template module
								$this->apply_template($bew_query,$template_id,$template_id_verification);
							?>	
							</div>							
							<?php 
						
						// Reset the post data to prevent conflicts with WP globals
						wp_reset_postdata();

						// If no posts are found display message
						else : ?>

							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'briefcase-elementor-widgets' ); ?></p>

						<?php
						// End post check
						endif;
				}
				
			break;
			
			case 'featured':
			case 'related':
			case 'upsell':
								
						$templates = $this->get_templates();
						$template_id_verification = '0';
							
						foreach ( $templates as $template ) {
							if ($template['template_id'] == $template_id ){
								$template_id_verification = '1';
							}  ;			
						}
						
						// Get args for query
						$args = $this->get_products_query_args();		
								
						// Build the WordPress query
						$bew_query = new \WP_Query( $args );
									
						// Output posts
						if ( $bew_query->have_posts() ) :							
														
							// Wrapper classes
							if ($woo_grid_layout == 'slider'){
								$wrap_classes = array('bew-woo-grid-slider', 'woocommerce', 'briefcasewp-row', 'clr' );	
							}else{
								$wrap_classes = array( 'bew-woo-grid', 'woocommerce');	
							}
							
							if ( 'masonry' == $grid_style ) {
								$wrap_classes[] = 'bew-masonry';
							}							

							if ( 'yes' == $equal_heights ) {
								$wrap_classes[] = 'match-height-grid';
							}

							$wrap_classes = implode( ' ', $wrap_classes ); 
							
							// Woo Grid Slider
							if ($woo_grid_layout == 'slider'){			
							$this->set_woo_grid_slider_attr();		
							}
									
							// Layout Shop Page
							?>					
							<div class="<?php echo esc_attr( $wrap_classes ); ?>" id="<?php echo $template_id ; ?>">
							<?php
																
							// Choose the layout type
									
							if ('featured' == $grid_type) {
								
								// Woo Grid Slider div
										
								if ($woo_grid_layout == 'slider'){
								?>
									<div class="products bew-featured-products bew-products-slider" <?php echo  $this->get_render_attribute_string( 'wrapper' ); ?>>						
									<?php
								}else{
									?>
									<div class="bew-products bew-featured-products bew-grid">								
									<?php
								}							
																								
									// Apply template module
									$this->apply_template($bew_query,$template_id,$template_id_verification);
								?>	
								</div>						
								<?php	
									
							} elseif ('related' == $grid_type) {
										
								if ($template_id_verification != '1'){
								
								if ($woo_grid_layout == 'slider'){
								?>
									<div class="products bew-related-products-default bew-products-slider" <?php echo  $this->get_render_attribute_string( 'wrapper' ); ?>>						
									<?php
								}else{
									?>
									<div class="bew-related-products-default bew-grid">								
									<?php
								}
								
									// Check current shop template
									$frontend = Frontend::instance();
									$check_template = $frontend->check_wc_shop_template();	
																		
									if($check_template  == ""){									
										?>								
										<span><?php _e( 'It seems you need to select a template for your related products.', 'briefcase-elementor-widgets' ); ?></span>								
										<?php
											
									} else {						
											
										// Apply template module
										$this->apply_template($bew_query,$template_id,$template_id_verification);
											
									}
									?>
									</div>						
									<?php
								} else {
									
									if ($woo_grid_layout == 'slider'){
									?>
										<div class="products bew-related-products bew-products-slider" <?php echo  $this->get_render_attribute_string( 'wrapper' ); ?>>						
										<?php
									}else{
										?>
										<div class="bew-related-products bew-grid">								
										<?php
									}
									
										// Apply template module
										$this->apply_template($bew_query,$template_id,$template_id_verification);
									?>
									</div>						
									<?php
								}
							} else {
								if ($woo_grid_layout == 'slider'){
								?>
									<div class="products bew-products-slider" <?php echo  $this->get_render_attribute_string( 'wrapper' ); ?>>						
									<?php
								}else{
									?>
									<div class="bew-products bew-grid">								
									<?php
								}
									// Apply template module
									$this->apply_template($bew_query,$template_id,$template_id_verification);
								?>
								</div>						
								<?php
							}			 
							?>
								
							</div><!-- .bew-woo-grid-->
							<?php
				
						// Reset the post data to prevent conflicts with WP globals
						wp_reset_postdata();

						// If no posts are found display message
						else : ?>

							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'briefcase-elementor-widgets' ); ?></p>

						<?php
						// End post check
						endif;
		break;
		
		case 'categories':
			
			// Wrapper category classes
				$wrap_classes_categories = array( 'bew-woo-grid', 'woocommerce');
				$wrap_classes_categories = implode( ' ', $wrap_classes_categories );
				
				echo '<div class="'. esc_attr( $wrap_classes_categories ) . '">';
			
					if ( empty($template_id)) {
						
						echo do_shortcode( $this->get_shortcode() );
						
					} else {
						
						// With custom template
						echo $this->bew_product_categories($template_id);			
					}
			
				echo '</div>';
			
		break;
		
		}
	// Enqueue shop JS
	wp_localize_script( 'woo-shop',
				'wooshopConfigs',
				array(
					'is_shop'                          => ( class_exists( 'WooCommerce' ) && Helper::is_shop() ),
					'categories_toggle'                => apply_filters( 'bew_categories_toggle', true ),				
					
				) );
		
	}
	
	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new bew_Widget_Woo_Grid() );