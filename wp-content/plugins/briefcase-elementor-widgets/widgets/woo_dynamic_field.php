<?php
namespace Briefcase;

use Elementor;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow	;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}

/**
 * Creates our custom Elementor widget
 *
 * Class briefcase elementor widget
 *
 * @package Elementor
 */
class Bew_Widget_Dynamic_Field extends Widget_Base {
		
	/**
	 * Get Widgets name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'bew_dynamic';
	}

	/**
	 * Get widgets title
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Woo Dynamic Field', 'briefcase-elementor-widgets' );
	}

	/**
	 * Get the current icon for display on frontend.
	 * The extra 'dtbaker-elementor-widget' class is styled differently in frontend.css
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-parallax';
	}

	/**
	 * Get available categories for this widget. Which is our own category for page builder options.
	 *
	 * @return array
	 */
	public function get_categories() {
		return [ 'briefcasewp-elements' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-modern',
				 'woo-qty',
				 'woo-avm',
				 'woo-add-to-cart',
				 'woo-single-product',				 
				 'wc-single-product',				 
				 'flexslider',				 
				 'jquery-slick',
				 'zoom',
				 'sticky-kit',
				 'woo-slider',
				 'woo-addtocart-ajax' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	/**
	 * We always show this item in the panel.
	 *
	 * @return bool
	 */
	public function show_in_panel() {
		return true;
	}
		
	/**
	 * This registers our controls for the widget. Currently there are none but we may add options down the track.
	 */
	protected function _register_controls() {
		
		
		$this->start_controls_section(
			'section_dynamic',
			[
				'label' => __( 'Dynamic Field', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'desc',
			[
				'label' => __( 'Choose from the available dynamic fields below.', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::RAW_HTML,
			]
		);

		$dynamic_select = array(
			'' => esc_html__( ' - choose - ', 'briefcase-elementor-widgets' ),
		);

		$dynamic_select = array_merge( $dynamic_select, $this->get_dynamic_fields( true ) );


		$this->add_control(
			'dynamic_field_value',
			[
				'label'   => esc_html__( 'Choose Field', 'briefcase-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $dynamic_select,
			]
		);
	if( class_exists( 'WooCommerce' ) ) {
		
		global $post;
		$post_type = get_post_type($post->ID);
				
		switch ( $post_type ) {
        case 'product':
		
		if ( is_product() ){
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart_single',
			]
			);			
			} 
			else {
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart_loop',
			]
			);			
			}			
        
		break;
        case 'elementor_library':
				
        $bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
		
		if (empty($bew_template_type)) {		
			if (!isset($_SESSION) && !headers_sent()) {
			session_start();
			}
			if (isset($_SESSION['selss'])) {				
			$bew_template_type = $_SESSION['selss'];					
			}
		}
		
		if (empty($bew_template_type)){
		$bew_template_type = get_post_meta($post->ID, '_elementor_template_type', true);
		}
				
			switch ( $bew_template_type ) {
			case 'woo-product':
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart_single',
			]
			);
						
			break;
			case 'woo-shop':
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart_loop',
			]
			);
						
			break;
			case 'product':
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart_single',
			]
			);
						
			break;
			case 'product-archive':
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart_loop',
			]
			);
						
			break;
			default:		
			$this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart',
			]
			); 
			}
		
        break;
		default:		
        $this->add_control(
			'product_add_to_cart_options',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'product_add_to_cart',
			]
			);   
        }
	}
				
		$this->end_controls_section();		
				
		$this->start_controls_section(
			'section_add_to_cart',
			[
				'label' => __( 'Add to cart', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',					
				]
			]
		);		
				
		
		$this->add_control(
			'product_type',
			[
				'label' 		=> __( 'Custom Add to cart by ID', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => 'product_add_to_cart',
				]
				
			]
		);
				
		$this->add_control(
			'product_id',
			[
				'label' 		=> __( 'Product ID', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder' 	=> __( 'Your Product ID', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_type' => 'yes',
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => 'product_add_to_cart',
					
                ]
					
			]
		);
				
		$this->add_control(
			'product_addtocart_text',
			[
				'label' => __( 'Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Add to Cart', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Add to Cart', 'briefcase-elementor-widgets' ),
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop', 'product_add_to_cart_single'],	
				]
			]
		);
		
		
		$this->add_control(
			'product_addtocart_icon',
			[
				'label' => __( 'Icon', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop', 'product_add_to_cart_single'],	
				]
			]
		);
		
		$this->add_control(
			'product_addtocart_icon_align',
			[
				'label' => __( 'Icon Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'briefcase-elementor-widgets' ),
					'right' => __( 'After', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop', 'product_add_to_cart_single'],	
				],
			]
		);

		$this->add_control(
			'product_addtocart_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop', 'product_add_to_cart_single'],	
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-align-icon-right i, {{WRAPPER}} #bew-cart-avm.bew-align-icon-right i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #bew-cart.bew-align-icon-left i, {{WRAPPER}} #bew-cart-avm.bew-align-icon-left i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'product_show_qty_box',
			[
				'label' 		=> __( 'Show Quantity Box', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],				
				]
				
			]
		);
		
		$this->add_control(
			'product_show_qty_text',
			[
				'label' 		=> __( 'Show Quantity Text', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],				
				]
				
			]
		);
		
		$this->add_control(
			'product_qty_text',
			[
				'label' => __( 'Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,				
				'placeholder' => __( 'Quantity', 'briefcase-elementor-widgets' ),
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart','product_add_to_cart_single'],
					'product_show_qty_text' => 'yes',
				]
			]
		);
						
		$this->add_responsive_control(
            'product_buttom_direction',
            [
                'label' => __( 'Style	', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'inline-block' => [
                        'title' => __( 'Horizontal', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    'block' => [
                        'title' => __( 'Vertical', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-v',
                    ]
                ],
                'default' => 'inline-block',
                'condition' => [
                    'product_show_qty_box' => 'yes'
                ],
				'prefix_class' => 'bew-cart-direction-',
				'selectors' => [
					'{{WRAPPER}} #bew-cart .single_add_to_cart_button, {{WRAPPER}} #bew-cart.button-by-id' => 'display: {{VALUE}}; vertical-align: middle; margin: 10px auto',
				],
                
            ]
        );
				

		$this->add_control(
			'field_preview',
			[
				'label'   => esc_html__( 'Code', 'briefcase-elementor-widgets' ),
				'type'    => Controls_Manager::RAW_HTML,
				'separator' => 'none',
				'show_label' => false,
				'raw' => '<div id="bew-dynamic-code"></div>',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => 'product_add_to_cart',
				]
			]
		);
		
		$this->add_control(
			'heading_addtocart_ajax',
			[
				'label' => __( 'Ajax Add to cart', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],					
				]
				
			]
		);

		$this->add_control(
			'product_addtocart_ajax',
			[
				'label' 		=> __( 'Enable', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'active',				
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
				]
			]
		);

		$this->add_control(
			'product_opencart_ajax',
			[
				'label' 		=> __( 'Open Menu Cart on Ajax', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'active',				
				'condition' => [                    
					'product_addtocart_ajax' => 'active',
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
				]
			]
		);
		
		$this->add_control(
			'heading_addtocart_visible',
			[
				'label' => __( 'Always Visible Mode', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
				]
				
			]
		);
		
		$this->add_control(
			'product_addtocart_visible_buttom',
			[
				'label' 		=> __( 'Activate Mode', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
				]
				
				
			]
		);
		
		$this->add_control(
			'product_showqtyv',
			[
				'label' 		=> __( 'Show Quantity Box', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [                    
					'product_addtocart_visible_buttom' => 'yes',
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
				]
			]
		);
		
		$this->add_control(
			'heading_addtocart_hover',
			[
				'label' => __( 'Overlay Mode', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop'],
				]
				
			]
		);
		
		$this->add_control(
			'product_addtocart_hover_buttom',
			[
				'label' 		=> __( 'Activate Mode', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop'],
				]
			]
		);
		
		$this->add_control(
            'overlay_button',
            [
                'label' => __( 'Overlay Button Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'custom' => [
						'title' => __( 'Custom', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-note',
					],
					'square' => [
						'title' => __( 'Square', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-square-o',
					],
					'circle' => [
						'title' => __( 'Circle', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-circle-o',
					],					
				],				
				'default' => 'custom',
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_addtocart_hover_buttom' => 'yes',
				]
            ]
        );
		
		$this->add_control(
			'heading_addtocart_underlines',
			[
				'label' => __( 'Underlines Button', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop'],					
				]
				
			]
		);
		
		$this->add_control(
			'product_addtocart_underlines',
			[
				'label' 		=> __( 'Activate Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
				'condition' => [                    
					'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop'],
				]
			]
		);
		
		$this->add_control(
			'heading_addtocart_product_variation',
			[
				'label' => __( 'Product Variation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],					
				]
				
			]
		);
		
		$this->add_control(
			'product_addtocart_variation_price_dynamic',
			[
				'label' 		=> __( 'Dynamic Variation Price', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);	
		
						
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_product_image',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_image',
				]	
			]
		);
		
		$this->add_control(
            'product_image_style',
            [
                'label' => __('Image Style', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'featured-image' 	=> __( 'Featured Image', 'briefcase-elementor-widgets' ),
                    'swap-image' 		=> __( 'Swap Image', 'briefcase-elementor-widgets' ),
					'slider-image' 		=> __( 'Slider Image', 'briefcase-elementor-widgets' ),	
                ],
				'prefix_class' => 'bew-product-image-type-',
                'default' => 'featured-image',
            ]
        );
		
		$this->add_control(
            'product_image_slider_layout',
            [
                'label' => __('Layout', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'briefcase-elementor-widgets' ),
                    'vertical' => __( 'Vertical', 'briefcase-elementor-widgets' ),
                ],
                'prefix_class' => 'bew-woo-slider-image-view-',
                'default' => 'horizontal',
				'condition' => [
                    'product_image_style' => 'slider-image',
				]
            ]
        );
		
		$this->add_control(
			'product_image_style_slider_thumbnails',
			[
				'label' 		=> __( 'Show slider thumbnails', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-slider-thumbnail-',
				'condition' => [
                    'product_image_style' => 'slider-image',
				]
			]
		);
				
		$this->add_control(
			'product_image_link',
			[
				'label' 		=> __( 'Image Link', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
			]
		);
		
		$this->add_control(
            'product_image_size',
            [
                'label' => __('Image Size', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'full' => __( 'Full', 'briefcase-elementor-widgets' ),
                    'woocommerce_thumbnail' => __( 'Woocommerce Thumbnail', 'briefcase-elementor-widgets' ),					
                ],               
                'default' => 'full'

            ]
        );
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_image_labels',
			[
				'label' => __( 'Labels', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_image',
				]	
			]
		);
		
		
		$this->add_control(
			'product_image_labels_new',
			[
				'label' 		=> __( 'New', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-image-new-show-',
				
			]
		);
		
		$this->add_control(
			'product_image_labels_new_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'New', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_image_labels_new' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'product_image_labels_new_days',
			[
				'label' => __( 'Published Days', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,				
				'default' 		=> 60,					
				'condition' => [
                    'product_image_labels_new' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'product_image_labels_featured',
			[
				'label' 		=> __( 'Featured', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-image-featured-show-',
				
			]
		);
		
		$this->add_control(
			'product_image_labels_featured_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hot', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Hot', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_image_labels_featured' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'product_image_labels_outofstock',
			[
				'label' 		=> __( 'Out of Stock', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-image-outofstock-show-',
				
			]
		);
		
		$this->add_control(
			'product_image_labels_outofstock_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Out of Stock', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Out of Stock', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_image_labels_outofstock' => 'yes',
				]
			]
		);		
		
		$this->add_control(
			'product_image_labels_sale',
			[
				'label' 		=> __( 'Sale', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-image-sale-show-',
				
			]
		);
		
		$this->add_control(
			'product_image_labels_sale_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Sale', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Sale', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_image_labels_sale' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'product_image_labels_sale_percent',
			[
				'label' 		=> __( 'Sale Percent', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-image-sale-percent-show-',
				'condition' => [
                    'product_image_labels_sale' => 'yes',
				]
			]
		);	
		
		$this->add_control(
            'product_image_labels_type',
            [
                'label' => __( 'Labels Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'square' => [
						'title' => __( 'Square', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-square-o',
					],
					'circle' => [
						'title' => __( 'Circle', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-circle-o',
					]
				],
				'default' => 'square',
				'prefix_class' => 'bew-image-labels-type-',
            ]
        );
		
		$this->end_controls_section();
				
		$this->start_controls_section(
			'section_gallery',
			[
				'label' => __( 'Gallery', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_gallery',
				]	
			]
		);
		
		$this->add_control(
            'product_gallery_layout',
            [
                'label' => __('Layout', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'briefcase-elementor-widgets' ),
                    'vertical' => __( 'Vertical', 'briefcase-elementor-widgets' ),
					'sticky' => __( 'Sticky', 'briefcase-elementor-widgets' ),
                ],
                'prefix_class' => 'bew-woo-gallery-view-',
                'default' => 'horizontal'

            ]
        );
		
			$this->add_control(
			'product_gallery_zoom',
			[
				'label' 		=> __( 'Zoom', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-zoom-show-',
				
			]
		);
				
		$this->add_control(
			'product_gallery_lightbox',
			[
				'label' 		=> __( 'Lightbox', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-lightbox-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_woo_default',
			[
				'label' 		=> __( 'Original WooCommerce Gallery', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-woo-default-',
				'description' 	=> __( 'Use this option to show the Original WooCommerce Gallery.', 'briefcase-elementor-widgets' ),
				
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_navigation',
			[
				'label' => __( 'Navigation', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_gallery',
				]	
			]
		);	
		
		$this->add_control(
			'product_gallery_arrows',
			[
				'label' 		=> __( 'Arrows', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-arrows-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_dots',
			[
				'label' 		=> __( 'Dots', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-dots-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_thumbnails',
			[
				'label' 		=> __( 'Thumbnails', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-thumbnails-show-',
				
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_labels',
			[
				'label' => __( 'Labels', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_gallery',
				]	
			]
		);		
		
		$this->add_control(
			'product_gallery_labels_new',
			[
				'label' 		=> __( 'New', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-new-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_new_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'New', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_gallery_labels_new' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'product_gallery_labels_new_days',
			[
				'label' => __( 'Published Days', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,				
				'default' 		=> 60,					
				'condition' => [
                    'product_gallery_labels_new' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'product_gallery_labels_featured',
			[
				'label' 		=> __( 'Featured', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-featured-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_featured_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Hot', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Hot', 'briefcase-elementor-widgets' ),				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_outofstock',
			[
				'label' 		=> __( 'Out of Stock', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-outofstock-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_outofstock_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Out of Stock', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Out of Stock', 'briefcase-elementor-widgets' ),				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_sale',
			[
				'label' 		=> __( 'Sale', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-sale-show-',
				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_sale_text',
			[
				'label' => __( 'Custom Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Sale', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Sale', 'briefcase-elementor-widgets' ),				
			]
		);
		
		$this->add_control(
			'product_gallery_labels_sale_percent',
			[
				'label' 		=> __( 'Sale Percent', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-gallery-sale-percent-show-',
				'condition' => [
                    'product_gallery_labels_sale' => 'yes',
				]
			]
		);		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_title',
				]	
			]
		);		
		
		$this->add_control(
			'product_title_link',
			[
				'label' 		=> __( 'Title Link', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				
			]
		);
		
		$this->add_control(
			'product_title_limit',
			[
				'label' 		=> __( 'Title Limit', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				
			]
		);
		
		$this->add_control(
			'product_title_limit_character',
			[
				'label' => __( 'Character Limit', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'condition' => [
                    'product_title_limit' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'product_title_limit_dots',
			[
				'label' 		=> __( 'Add "..."', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'product_title_limit' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'product_title_limit_wordcutter',
			[
				'label' 		=> __( 'Dont break words in title', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'product_title_limit' => 'yes',
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_price',
			[
				'label' => __( 'Price', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_price',
				]	
			]
		);
		
		$this->add_control(
			'product_price_absolute',
			[
				'label' 		=> __( 'Position Absolute', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'heading_product_price_RS',
			[
				'label' => __( 'Regular/Sale Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,				
				'separator' => 'before',				
			]
		);
		
		
		$this->add_control(
			'product_price_regular',
			[
				'label' 		=> __( 'Show Regular Price', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);
		
		$this->add_control(
			'product_price_sale',
			[
				'label' 		=> __( 'Show Sale Price', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);
		
		$this->add_control(
			'heading_product_price_variation',
			[
				'label' => __( 'Variation Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,				
				'separator' => 'before',				
			]
		);
		
		
		$this->add_control(
			'product_price_low',
			[
				'label' 		=> __( 'Show Lowest Price', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);
		
		$this->add_control(
			'product_price_low_text',
			[
				'label' => __( 'Text Before Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'From:', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_price_low' => 'yes',
				]
			]
		);
				
		$this->add_control(
			'product_price_high',
			[
				'label' 		=> __( 'Show Highest Price', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);
		
		$this->add_control(
			'product_price_high_text',
			[
				'label' => __( 'Text Before Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Up to:', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_price_high' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'product_price_hide',
			[
				'label' 		=> __( 'Hide Price Until Select', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_tabs_title',
            [
                'label' => __( 'Tabs', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_tabs',
                ]
				
            ]
        );

        $this->add_control(
            'tab_layout',
            [
                'label' => __('Layout', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => __( 'Horizontal', 'briefcase-elementor-widgets' ),
                    'vertical' => __( 'Vertical', 'briefcase-elementor-widgets' ),
                ],
                'prefix_class' => 'bew-woo-tabs-view-',
                'default' => 'horizontal'

            ]
        );    

        $repeater = new Repeater();


        if(is_singular('elementor_library')){
            $registered_tabs = $this->get_woo_registered_tabs();			
        }
        $registered_tabs['description'] = __('Description', 'briefcase-elementor-widgets');
        $registered_tabs['additional_information'] = __('Additional Information','briefcase-elementor-widgets');
        $registered_tabs['reviews'] = __('Reviews','briefcase-elementor-widgets');
        $registered_tabs['custom'] = __('Custom','briefcase-elementor-widgets');
		
        $repeater->add_control(
            'tab_type',
            [
                'label' => __( 'Tab Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SELECT,
                'options' => $registered_tabs,
                'default' => 'description',
            ]
        );

        $repeater->add_control(
            'tab_title',
            [
                'label' => __( 'Tab Title', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Description',
            ]
        );

        $repeater->add_control(
            'custom_tab_content',
            [
                'label' => __( 'Tab Content', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
                'default' => '',
                'condition' => [
                    'tab_type' => 'custom',
                ],
            ]
        );
		
		global $post;
		$bewglobal = get_post_meta($post->ID, 'briefcase_apply_global', true);
		if ($bewglobal == 'off' ){
		$repeater->add_control(
            'custom_description_content',
            [
                'label' => __( 'Tab Content', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '',
                'condition' => [
                    'tab_type' => 'description',
                ],
            ]
        );
		}
		
        $this->add_control(
            'tabs',
            [
                'label' => __( 'Tabs', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::REPEATER,
                'fields' => array_values($repeater->get_controls()),
                'default' => [
                    [
                        'tab_type' => 'description',
                        'tab_title' => __('Description','briefcase-elementor-widgets')
                    ],
                    [
                        'tab_type' => 'additional_information',
                        'tab_title' => __('Additional Information','briefcase-elementor-widgets')
                    ],
                    [
                        'tab_type' => 'reviews',
                        'tab_title' => __('Reviews','briefcase-elementor-widgets')
                    ],
                ],
                'title_field' => '{{{ tab_title }}}'

            ]
        );

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_meta',
			[
				'label' => __( 'Meta', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_meta',
				]
				
			]
		);
		
		$this->add_control(
			'product_meta_sku',
			[
				'label' 		=> __( 'Show Product SKU', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',	
				'prefix_class' => 'bew-product-meta-sku-show-',
				
			]
		);
		
		$this->add_control(
			'product_meta_categories',
			[
				'label' 		=> __( 'Show Product Categories', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				'prefix_class' => 'bew-product-meta-categories-show-',
			]
		);
		
		$this->add_control(
			'product_meta_tags',
			[
				'label' 		=> __( 'Show Product Tags', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',		
				'prefix_class' => 'bew-product-meta-tags-show-',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_rating',
			[
				'label' => __( 'Rating', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_rating',
				]
				
			]
		);
		
		$this->add_control(
			'product_rating_start',
			[
				'label' 		=> __( 'Show Rating Stars', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',	
				'prefix_class' => 'bew-product-rating-stars-show-',
				
			]
		);
		
		$this->add_control(
			'product_rating_count',
			[
				'label' 		=> __( 'Show Rating Review Count', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',	
				'prefix_class' => 'bew-product-rating-count-show-',
				
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_review',
			[
				'label' => __( 'Review', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'product_comments',
				]
				
			]
		);
		
		$this->add_control(
			'product_review_layout',
			[
				'label' => __( 'Layout', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
			'options' => [				
				'' => __( 'None', 'briefcase-elementor-widgets' ),
				'vertical' => __( 'Vertical', 'briefcase-elementor-widgets' ),	
				'horizontal' => __( 'Horizontal', 'briefcase-elementor-widgets' ),							
			],
			'prefix_class' => 'bew-review-layout-',			
			]
		);
		
		$this->add_control(
			'product_review_slider',
			[
				'label' 		=> __( 'Review Slider', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',	
				'prefix_class' => 'bew-review-show-',				
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cat_title',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'category_title',
				]
				
			]
		);
		
		$this->add_control(
			'cat_title_count',
			[
				'label' 		=> __( 'Display Products Count', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'cat_title_absolute',
			[
				'label' 		=> __( 'Position Absolute', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
								
			]
		);
		
		$this->add_control(
			'cat_title_absolute_translate',
			[
				'label' 		=> __( 'Translate Effect', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'cat_title_absolute' => 'yes',
				]
								
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cat_image',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'category_image',
				]
				
			]
		);
		
		$this->add_control(
			'cat_image_scale',
			[
				'label' 		=> __( 'Scale Effect', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-cat-image-scale-show-',
								
			]
		);
		
		$this->add_control(
			'cat_image_hover_black',
			[
				'label' 		=> __( 'Overlay Effect', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_general',
			[
				'label' => __( 'General', 'briefcase-elementor-widgets' ),
			]
		);	
				
		$this->add_responsive_control(
			'align',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_title',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',				
				'selector' => '{{WRAPPER}} .product_title',
			]
		);
				
		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'title_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_title' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_title_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'title_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_title:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'title_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_title:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'title_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .product_title:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'title_hover_animation',
			[
				'label' => __( 'Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'title_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .product_title',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'title_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .product_title',
			]
		);
		
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __( 'Text Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_responsive_control(	
			'title_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .product_title' => ' height: {{SIZE}}{{UNIT}};',					
				],				
			]
		);
		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_price',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',				
				'selector' => '{{WRAPPER}} .bew-price-grid .price, {{WRAPPER}} .bew-price-grid .price ins',
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price .amount, {{WRAPPER}} .bew-price-grid .price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'price_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-price-grid .price' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'heading_price_regular',
			[
				'label' => __( 'Regular Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'product_price_regular' => 'yes',
                ]
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_regular_typography',				
				'selector' => '{{WRAPPER}} .bew-price-grid .price del .amount',
				'condition' => [
                    'product_price_regular' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'price_regular_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-price-grid .price del .amount' => 'color: {{VALUE}};',
				],
				'condition' => [
                    'product_price_regular' => 'yes',
                ]
				
			]
		);
				
		$this->add_control(
			'price_regular_opacity',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'default' => [
					'size' => 0.5,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .product-on-sale.show-price-regular .price del' => 'opacity: {{SIZE}};',
				],
				'condition' => [
                    'product_price_regular' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'price_regular_linethrough',
			[
				'label' 		=> __( 'Line through', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'line-through-',
				'condition' => [
                    'product_price_regular' => 'yes',
                ]				
			]
		);
		
		$this->add_control(
			'price_regular_linethrough_color',
			[
				'label' 		=> __( 'Line Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}}.line-through-yes .bew-price-grid .product-on-sale.show-price-regular .price del' => 'color: {{VALUE}};',
				],
				'condition' => [
					'product_price_regular' => 'yes',
                    'price_regular_linethrough' => 'yes',
					
                ]
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-price-grid .price',
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'price_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'price_box_shadow',
				'selector' => '{{WRAPPER}} .bew-price-grid .price',
			]
		);
		
		$this->add_responsive_control(
			'price_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'price_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		
		
		$this->add_control(
			'heading_price_dimension',
			[
				'label' => __( 'Dimensions', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
				
			]
		);		
		
		$this->add_responsive_control(
			'price_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'width: {{VALUE}}px;',						
				],					
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'price_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'height: {{VALUE}}px; line-height: {{VALUE}}px;',
				],
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'heading_price_position',
			[
				'label' => __( 'Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
				
			]
		);
		
		$this->add_responsive_control(
			'price_position_top',
			[
				'label' => __( 'Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'top: {{VALUE}}px; bottom:unset;',
				],
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'price_position_bottom',
			[
				'label' => __( 'Bottom', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'bottom: {{VALUE}}px; top:unset;',
				],
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'price_position_left',
			[
				'label' => __( 'Left', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'left: {{VALUE}}px; right:unset;',
				],
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'price_position_right',
			[
				'label' => __( 'Right', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'right: {{VALUE}}px; left:unset;',
				],
				'condition' => [
                    'product_price_absolute' => 'yes',
                ]
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => __( 'Meta', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_meta',
                ]
			]
		);
				
		$this->add_control(
			'meta_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_meta' => 'background: {{VALUE}};',
				],
				
			]
		);		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'meta_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .product_meta',
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'meta_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_meta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
				
		$this->add_responsive_control(
			'meta_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product_meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
						
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_meta_titles_style',
			[
				'label' => __( 'Titles', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_meta',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography_titles',				
				'selector' => '{{WRAPPER}} .bew-product-meta table.product_meta td.label',
			]
		);
		
		$this->add_control(
			'meta_color_titles',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .product_meta .posted_in td.label , {{WRAPPER}} .product_meta .tagged_as td.label , {{WRAPPER}} .product_meta .sku_wrapper td.label, {{WRAPPER}} .product_meta td.label' => 'color: {{VALUE}};',
				], 
			]
		);
		
		$this->add_responsive_control(
			'meta_padding_titles',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-product-meta table.product_meta td.label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_meta_content_style',
			[
				'label' => __( 'Content', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_meta',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography_content',				
				'selector' => '{{WRAPPER}} .bew-product-meta table.product_meta td.value',
			]
		);
		
		$this->start_controls_tabs( 'tabs_meta_style' );
		
		$this->start_controls_tab(
			'tab_meta_content_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'meta_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_meta .posted_in a , {{WRAPPER}} .product_meta .tagged_as a,{{WRAPPER}} .product_meta .sku_wrapper .sku, {{WRAPPER}} .product_meta tr a' => 'color: {{VALUE}};',
				],
			] 
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_meta_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'meta_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product_meta .posted_in a:hover , {{WRAPPER}} .product_meta .tagged_as a:hover, {{WRAPPER}} .product_meta tr a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		
		$this->add_responsive_control(
			'meta_padding_content',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-product-meta table.product_meta td.value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_rating_style',
			[
				'label' => __( 'Rating', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_rating',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'rating_typography',				
				'selector' => '{{WRAPPER}} .bew-rating .woocommerce-product-rating, {{WRAPPER}} .bew-rating .woocommerce-product-rating .star-rating',
			]
		);
		
		$this->start_controls_tabs( 'tabs_rating_style' );
		
		$this->start_controls_tab(
			'tab_rating_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'rating_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating a' => 'color: {{VALUE}};',
				],
			] 
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_rating_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'rating_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_control(
			'rating_color_star',
			[
				'label' => __( 'Stars Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating .star-rating span, {{WRAPPER}} .bew-rating .woocommerce-product-rating .star-rating  span::before' => 'color: {{VALUE}};',
				], 
			]
		);		
		
		$this->add_control(
			'rating_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating' => 'background: {{VALUE}};',
				],
				
			]
		);		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'rating_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-rating .woocommerce-product-rating',
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'rating_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		
		$this->add_responsive_control(
			'rating_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'rating_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-rating .woocommerce-product-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
						
		$this->end_controls_section();		
		
		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => __( 'Add to cart', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_typo',
				'selector' 		=> '{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'button_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn-underlines svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'button_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-cart .button:hover,{{WRAPPER}} #bew-cart .added_to_cart:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn-underlines:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-cart .button:hover, {{WRAPPER}} #bew-cart .added_to_cart:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .button:hover, {{WRAPPER}} #bew-cart .added_to_cart:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart',
			]
		);
				
				
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'button_margin',
			[
				'label' => __( 'Button Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .button, {{WRAPPER}} #bew-cart .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_overlay_style',
			[
				'label' => __( 'Overlay Mode', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_addtocart_hover_buttom' => 'yes',
                ]
			]
		);
						
		$this->add_control(
			'overlay_heading_button',
			[
				'label' => __( 'Add to Cart Button', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,				
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',					
				],
			]
		);
		
		$this->add_responsive_control(
			'overlay_button_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart, {{WRAPPER}} #bew-cart.bew-add-to-cart .button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
							
		$this->add_responsive_control(
			'button_position_top',
			[
				'label' => __( 'Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart' => 'top: {{VALUE}}px;',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'button_position_bottom',
			[
				'label' => __( 'Bottom', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart' => 'bottom: {{VALUE}}px;',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'button_position_left',
			[
				'label' => __( 'Left', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart' => 'left: {{VALUE}}px;',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'button_position_right',
			[
				'label' => __( 'Right', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart' => 'right: {{VALUE}}px;',
				],
				
			]
		);
		
		$this->add_control(
			'button_absolute_hover_animation',
			[
				'label' => __( 'Hover Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
			'options' => [
				'' => __( 'None', 'briefcase-elementor-widgets' ),
				'fade-in' => __( 'Fade In', 'briefcase-elementor-widgets' ),
				'fade-up' => __( 'Fade Up', 'briefcase-elementor-widgets' ),				
			],
			'prefix_class' => 'hover-animation-',
			]
		);
				
		$this->add_responsive_control(	
			'overlay_height',
			[
				'label' => __( 'Dimensions', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.hover-buttom.btn-square .button' => ' width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #bew-cart.hover-buttom.btn-circle .button' => ' width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [					
					'overlay_button' => [ 'square', 'circle'],
				],
			]
		);
		
		$this->add_control(
			'overlay_heading_button_view_cart',
			[
				'label' => __( 'View Cart Button', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,				
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',						
				],
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(	
			'overlay_height_view_cart',
			[
				'label' => __( 'Dimensions', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.hover-buttom .added_to_cart' => ' width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',					
				],				
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'overlay_view_cart_button_typo',
				'selector' 		=> '{{WRAPPER}} #bew-cart.hover-buttom .added_to_cart'
			]
		);
		
		$this->add_responsive_control(
			'overlay_view_cart_button_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.hover-buttom .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'overlay_view_cart_button_margin',
			[
				'label' => __( 'Button Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.hover-buttom .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
				
		
		$this->start_controls_section(
			'section_qty_style',
			[
				'label' => __( 'Quantity Box', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
					
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'qty_typo',
				'selector' 		=> '{{WRAPPER}} .quantity .qty,{{WRAPPER}} .quantity .minus , {{WRAPPER}} .quantity .plus',
			]
		);
		
		$this->start_controls_tabs( 'tabs_qty_style' );

		$this->start_controls_tab(
			'tab_qty_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'qty_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .quantity .qty,{{WRAPPER}} .quantity .minus , {{WRAPPER}} .quantity .plus' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'qty_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .quantity .qty,{{WRAPPER}} .quantity .minus , {{WRAPPER}} .quantity .plus' => 'background: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_qty_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'qty_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .quantity .qty:hover,{{WRAPPER}} .quantity .minus:hover , {{WRAPPER}} .quantity .plus:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'qty_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .quantity .qty:hover,{{WRAPPER}} .quantity .minus:hover , {{WRAPPER}} .quantity .plus:hover' => 'background-color: {{VALUE}};' ,
					
				],
				
			]
		);

			
		$this->add_control(
			'qty_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .quantity .qty:hover,{{WRAPPER}} .quantity .minus:hover , {{WRAPPER}} .quantity .plus:hover' => 'border-color: {{VALUE}};' ,					
				],
				
			]
		);
		
		$this->add_control(
			'qty_hover_animation',
			[
				'label' => __( 'Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'qty_size_width',
			[
				'label' => __( 'Quantity Box Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .show-qty .quantity .qty ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .qty' => 'width: {{SIZE}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'qty_size_height',
			[
				'label' => __( 'Quantity Box Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .show-qty .quantity .qty ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .qty' => 'height: {{SIZE}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'control_qty_width',
			[
				'label' => __( '(-/+) Box Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .show-qty .quantity .minus , {{WRAPPER}} .show-qty .quantity .plus ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .minus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .plus' => 'width: {{SIZE}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'control_qty_height',
			[
				'label' => __( '(-/+) Box Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .show-qty .quantity .minus , {{WRAPPER}} .show-qty .quantity .plus ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .minus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .plus' => 'height: {{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'qty_separation',
			[
				'label' => __( 'Quantity Box Separation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} #bew-cart.show-qty .quantity .qty ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .qty' => 'margin-left: {{SIZE}}{{UNIT}} !important ; margin-right: {{SIZE}}{{UNIT}} !important;',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'qty_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #bew-cart.show-qty .quantity .qty,{{WRAPPER}} #bew-cart.show-qty .quantity .minus , {{WRAPPER}} #bew-cart.show-qty .quantity .plus ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .qty,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .minus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .plus',
				'separator' => 'before',
				
			]
		);
		
		$this->add_responsive_control(
		'border_width_minus',
			[
			'label' => __( 'Border Width Minus Box', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} #bew-cart.show-qty .quantity .minus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .minus ' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
			'condition' => [
					'qty_border_border!' => '',
			],
		]
		);
		
		$this->add_responsive_control(
		'border_width_plus',
			[
			'label' => __( 'Border Width Plus Box', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} #bew-cart.show-qty .quantity .plus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .plus ' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
			'condition' => [
					'qty_border_border!' => '',
			],
		]
		);
		
		$this->add_control(
			'qty_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.show-qty .quantity .qty,{{WRAPPER}} #bew-cart.show-qty .quantity .minus , {{WRAPPER}} #bew-cart.show-qty .quantity .plus ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .qty,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .minus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .plus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
								
		$this->add_responsive_control(
			'qty_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.show-qty .quantity .qty,{{WRAPPER}} #bew-cart.show-qty .quantity .minus , {{WRAPPER}} #bew-cart.show-qty .quantity .plus ,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .qty,{{WRAPPER}} .product-by-id.show-qty #bew-qty  .quantity .minus , {{WRAPPER}} .product-by-id.show-qty #bew-qty .quantity .plus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'qty_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.show-qty .quantity, {{WRAPPER}} .product-by-id.show-qty #bew-qty' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
		
		$this->add_control(
			'qty_text',
			[
				'label' => __( 'Quantity Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,				
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',						
				],
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'qty_text_typo',
				'selector' 		=> '{{WRAPPER}} .bew-qty-text',
			]
		);
		
		$this->add_responsive_control(
			'qty_text__color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-qty-text' => 'color: {{VALUE}};' ,
					
				],
				
			]
		);
		
		$this->add_responsive_control(
			'qty_text_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-qty-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],					
			]
		);
		


        $this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_avm_style',
			[
				'label' => __( 'Always Visible Mode', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_single'],
                ]
			]
		);
						
		$this->add_control(
			'avm_heading_bar',
			[
				'label' => __( 'Top Bar', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,				
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',
				],
			]
		);
		
		$this->add_responsive_control(	
			'avm_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .productadd' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'avm_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .productadd' => 'background-color: {{VALUE}};' ,
					
				],
				
			]
		);
		
		$this->add_responsive_control(
			'avm_bar_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .productadd' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_control(
			'avm_heading_title',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'avm_title_typo',
				'selector' 		=> '{{WRAPPER}} .product-title h2, {{WRAPPER}} .product-title p',
			]
		);
		
		$this->add_responsive_control(
			'avm_title_color',
			[
				'label' 		=> __( 'Title Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .productadd, {{WRAPPER}} .product-title h2, {{WRAPPER}} .product-title p' => 'color: {{VALUE}};',
				],
			]
		);
				
		$this->add_control(
			'avm_heading_price',
			[
				'label' => __( 'Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'avm_price_typo',
				'selector' 		=> '{{WRAPPER}} .product-buttom p',
			]
		);
		
		$this->add_responsive_control(
			'avm_price_color',
			[
				'label' 		=> __( 'Price Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .always-visible .amount' => 'color: {{VALUE}};',
				],
			]
		);		
		
		$this->add_control(
			'avm_heading_buttom',
			[
				'label' => __( 'Add to Cart Buttom', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'dynamic_field_value' => 'product_add_to_cart',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'avm_button_typo',
				'selector' 		=> '{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart',
			]
		);
		
		$this->start_controls_tabs( 'avm_tabs_button_style' );

		$this->start_controls_tab(
			'avm_tab_button_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_responsive_control(
			'avm_button_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'avm_button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'avm_tab_button_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_responsive_control(
			'avm_button_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .always-visible button.button:hover,{{WRAPPER}} .always-visible #bew-cart-avm .button:hover, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'avm_button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' 	=> [
					'{{WRAPPER}} .always-visible button.button:hover,{{WRAPPER}} .always-visible #bew-cart-avm .button:hover, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_responsive_control(
			'avm_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .always-visible button.button:hover,{{WRAPPER}} .always-visible #bew-cart-avm .button:hover, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'avm_hover_animation',
			[
				'label' => __( 'Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();		
		
		$this->add_control(
		'avm_button_border_type',
			[
			'label' => __( 'Border', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'' => __( 'None', 'elementor' ),
				'solid' => __( 'Solid', 'briefcase-elementor-widgets' ),
				'double' => __( 'Double', 'briefcase-elementor-widgets' ),
				'dotted' => __( 'Dotted', 'briefcase-elementor-widgets' ),
				'dashed' => __( 'Dashed', 'briefcase-elementor-widgets' ),
			],
			'selectors' => [
				'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'border-style: {{VALUE}};',
			],
		]
		);

		$this->add_responsive_control(
		'avm_button_border_width',
			[
			'label' => __( 'Width', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'avm_button_border_type!' => '',
			],
		]
		);

		$this->add_responsive_control(
			'avm_button_border_color',
			[
			'label' => __( 'Color', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'avm_button_border_type!' => '',
			],
		]
		);
		
		$this->add_responsive_control(
			'avm_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'avm_button_box_shadow',
				'selector' => '{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart',
			]
		);
				
				
		$this->add_responsive_control(
			'avm_button_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'avm_button_margin',
			[
				'label' => __( 'Button Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .always-visible button.button,{{WRAPPER}} .always-visible #bew-cart-avm .button, {{WRAPPER}} .always-visible #bew-cart-avm .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_view_cart_style',
			[
				'label' => __( 'View Cart Button', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => [ 'product_add_to_cart', 'product_add_to_cart_loop'],
					'product_addtocart_hover_buttom' => '',
                ]
			]
		);
		
		$this->add_responsive_control(	
			'view_cart_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart .added_to_cart' => ' width: {{SIZE}}{{UNIT}};',					
				],				
			]
		);
		
		$this->add_responsive_control(	
			'view_cart_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart .added_to_cart' => ' height: {{SIZE}}{{UNIT}};',					
				],				
			]
		);		

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'view_cart_button_typo',
				'selector' 		=> '{{WRAPPER}} #bew-cart.bew-add-to-cart .added_to_cart'
			]
		);
		
		$this->add_responsive_control(
			'view_cart_button_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart .added_to_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'view_cart_button_margin',
			[
				'label' => __( 'Button Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart.bew-add-to-cart .added_to_cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_divider_style',
			[
				'label' => __( 'Divider', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
                ]
			]
		);
					
		$this->add_control(
			'divider_weight',
			[
				'label' => __( 'Weight', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} form.cart' => 'border-top: {{SIZE}}{{UNIT}} solid ; border-bottom: {{SIZE}}{{UNIT}} solid;',
				],
			]
		);
		
		$this->add_control(
			'divider_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} form.cart' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_message_style',
			[
				'label' => __( 'Message', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',
					'product_add_to_cart_options' => 'product_add_to_cart_single',	
                ]
			]
		);
		
		$this->add_control(
			'message_position',
			[
				'label' => __( 'Position Top', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'selectors' => [
					' .woocommerce-message' => 'top: {{SIZE}}{{UNIT}}; position: absolute; z-index: 9;',
				],
			]
		);
		
		$this->add_responsive_control(
			'message_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					' .woocommerce-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'message_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					' .woocommerce-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_variation_style',
			[
				'label' => __( 'Product Variation', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_add_to_cart',					
                ]
			]
		);
		
		$this->add_responsive_control(
            'product_variation_layout',
            [
                'label' => __( 'Layout', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'inline' => [
                        'title' => __( 'Inline', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-h',
                    ], 
					'stacked' => [
                        'title' => __( 'Stacked', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-v',
                    ]					                   
                ],
                'default' => 'inline', 
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'prefix_class' => 'bew-variation-layout-%s-',		
                
            ]
        );
		
		$this->add_control(
			'product_variation_spacing',
			[
				'label' => __( 'Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .variations' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		
		$this->add_control(
			'product_variation_space_between',
			[
				'label' => __( 'Space Between', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations' => 'border-spacing: 0 {{SIZE}}{{UNIT}}; border-collapse: separate;',
				],
			]
		);
		
		$this->add_responsive_control(
			'product_variation_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'product_variation_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #bew-cart form.cart table.variations',							
			]
		);
		
		$this->add_control(
			'heading_product_variation_label',
			[
				'label' => __( 'Label', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'product_variation_label_show',
			[
				'label' 		=> __( 'Hide Labels', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-hide-variation-label-',
			]
		);
		
		$this->add_control(
			'product_variation_label_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations label' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_variation_label_typography',
				'selector' => '{{WRAPPER}} #bew-cart form.cart table.variations label',
			]
		);		
				
		$this->add_responsive_control(
			'product_variation_label_width',
			[
				'label' => __( 'Label Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .variations td.label' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'product_variation_label_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .variations td.label,  {{WRAPPER}} #bew-cart form.cart .variations td.value .theme-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		
		$this->add_control(
			'heading_product_variation_drop_down',
			[
				'label' => __( 'Select Fields', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'product_variation_drop_down_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations td.value select, {{WRAPPER}} #bew-cart form.cart table.variations td.value .theme-select' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_variation_drop_down_bg_color',
			[
				'label' => __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations td.value:before , {{WRAPPER}} #bew-cart form.cart table.variations td.value select ,{{WRAPPER}} #bew-cart form.cart table.variations td.value .theme-select' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_variation_drop_down_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations td.value:before, {{WRAPPER}} #bew-cart form.cart table.variations td.value select ,{{WRAPPER}} #bew-cart form.cart table.variations td.value .theme-select' => 'border: 1px solid {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_variation_drop_down_typography',
				'selector' => '{{WRAPPER}} #bew-cart form.cart table.variations td.value select, {{WRAPPER}} #bew-cart form.cart table.variations td.value:before, {{WRAPPER}} #bew-cart form.cart table.variations td.value .theme-select',
			]
		);

		$this->add_control(
			'product_variation_drop_down_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart table.variations td.value:before, {{WRAPPER}} #bew-cart form.cart table.variations td.value select, {{WRAPPER}} #bew-cart form.cart table.variations td.value .theme-select' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);
						
		$this->add_responsive_control(
			'product_variation_drop_down_width',
			[
				'label' => __( 'Select Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .variations td.value select,  {{WRAPPER}} #bew-cart form.cart .variations td.value .theme-select' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
				
		$this->add_responsive_control(
			'product_variation_drop_down_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .variations td.value select,  {{WRAPPER}} #bew-cart form.cart .variations td.value .theme-select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->add_responsive_control(
            'product_variation_drop_down_reset',
            [
                'label' => __( 'Clear Button', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'stacked' => [
                        'title' => __( 'Stacked', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-v',
                    ],
					'inline' => [
                        'title' => __( 'Inline', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-h',
                    ]                    
                ],
                'default' => 'stacked',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'prefix_class' => 'bew-variation-reset-%s-',
                
            ]
        );
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'product_variation_drop_down_reset_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #bew-cart form.cart .variations .reset_variations',							
			]
		);
		
		
		
		$this->add_control(
			'heading_product_variation_description',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_variation_description_typography',
				'selector' => '{{WRAPPER}} #bew-cart form.cart .single_variation_wrap .woocommerce-variation-description',
			]
		);
		
		$this->add_control(
			'product_variation_description_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .single_variation_wrap .woocommerce-variation-description' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'product_variation_description_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart form.cart .single_variation_wrap .woocommerce-variation-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);	
				
		$this->add_control(
			'heading_product_variation_price',
			[
				'label' => __( 'Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_variation_typography',				
				'selector' => '{{WRAPPER}} #bew-cart .woocommerce-variation-price .price',
			]
		);
		
		$this->add_control(
			'price_variation_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} #bew-cart .woocommerce-variation-price .price .amount, {{WRAPPER}} #bew-cart .woocommerce-variation-price .price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'price_variation_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-cart .woocommerce-variation-price .price' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'price_variation_text',
			[
				'label' => __( 'Text Before Price', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Total Price', 'briefcase-elementor-widgets' ),				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_variation_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} #bew-cart .woocommerce-variation-price .price',							
			]
		);
		
		$this->add_control(
			'price_variation_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .woocommerce-variation-price .price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'price_variation_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .woocommerce-variation-price .price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'price_variation_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-cart .woocommerce-variation-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_image',
                ]
			]
		);
		
		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Max Width', 'briefcase-elementor-widgets' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'image_equal_heights',
			[
				'label' 		=> __( 'Equal Heights', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_responsive_control(
			'image_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image .woo-entry-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
                    'image_equal_heights' => 'yes',
                ]
			]
		);
		
		
		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .bew-product-image img',
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .bew-product-image:hover img',
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'image_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Image Border', 'briefcase-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .bew-product-image img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .bew-product-image img',
			]
		);
		
		$this->add_responsive_control(
			'img_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'img_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-product-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_image_labels_style',
			[
				'label' => __( 'Labels', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_image',
				]	
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'image_labels_typography',				
				'selector' => '{{WRAPPER}} .product-image .bew-product-badges span, {{WRAPPER}} .product-image .bew-product-badges span.onsale',
			]
		);
		
		$this->add_control(
			'image_labels_color',
			[
				'label' 		=> __( 'Labels Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-image .bew-product-badges span' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'image_label_new_color',
			[
				'label' 		=> __( 'New Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-image .bew-product-badges span.new' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'image_label_featured_color',
			[
				'label' 		=> __( 'Featured Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-image .bew-product-badges span.hot' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'image_label_outofstock_color',
			[
				'label' 		=> __( 'Out of Stock Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-image .bew-product-badges span.outofstock' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'image_label_sale_color',
			[
				'label' 		=> __( 'Sale Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .product-image .bew-product-badges span.onsale' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_label_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .product-image .bew-product-badges span',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'image_label_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_control(
			'heading_image_label_title_position',
			[
				'label' => __( 'Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'image_label_position_top',
			[
				'label' => __( 'Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges' => 'top: {{VALUE}}px; bottom:unset;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'image_label_position_bottom',
			[
				'label' => __( 'Bottom', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges' => 'bottom: {{VALUE}}px; top:unset;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'image_label_position_left',
			[
				'label' => __( 'Left', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges' => 'left: {{VALUE}}px; right:unset;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'image_label_position_right',
			[
				'label' => __( 'Right', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges' => 'right: {{VALUE}}px; left:unset;',
				],				
			]
		);
				
		$this->add_responsive_control(
			'image_label_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges span' => 'width: {{VALUE}}px;',						
				],
			]
		);
		
		$this->add_responsive_control(
			'image_label_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges span' => 'height: {{VALUE}}px; line-height: {{VALUE}}px;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'image_label_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'image_label_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-image .bew-product-badges span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_gallery_style',
			[
				'label' => __( 'Gallery', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_gallery',
                ]
			]
		);
		
		$this->add_control(
            'heading_gallery_image',
            [
                'label' => __( 'Image', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::HEADING,
                
            ]
        );
				
		$this->add_responsive_control(
			'gallery_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'gallery_space',
			[
				'label' => __( 'Max Width', 'briefcase-elementor-widgets' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images' => 'max-width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->start_controls_tabs( 'gallery_effects' );

		$this->start_controls_tab( 'gallery_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'gallery_css_filters',
				'selector' => '{{WRAPPER}} .bew-gallery-images .images',
			]
		);

		$this->add_control(
			'gallery_opacity',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images' => 'opacity: {{SIZE}} !important;',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'gallery_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'gallery_css_filters_hover',
				'selector' => '{{WRAPPER}} .bew-gallery-images .images:hover',
			]
		);

		$this->add_control(
			'gallery_opacity_hover',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'gallery_background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images:hover' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'gallery_hover_animation',
			[
				'label' => __( 'Hover Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'gallery_border',
				'label' => __( 'Image Border', 'briefcase-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .bew-gallery-images .images .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gallery_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'gallery_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .bew-gallery-images .images .woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image',
			]
		);
				
		$this->add_control(
			'gallery_arrows_color',
			[
				'label' 		=> __( 'Arrows Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images button.slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
            'gallery_arrows_size',
            [
                'label' => __( 'Arrows Size', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-gallery-images button.slick-arrow' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}} .bew-gallery-images button.slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		
		
		$this->add_control(
			'gallery_dots_color',
			[
				'label' 		=> __( 'Dot Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .slick-dots li button' => 'background-color: {{VALUE}} !important;',
				],
			]
		);
		
		$this->add_control(
            'gallery_dots_size',
            [
                'label' => __( 'Dots Size', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 6,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-gallery-images .slick-dots li button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_control(
			'gallery_dots_color_active',
			[
				'label' 		=> __( 'Dots Active Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .slick-dots li.slick-active button' => 'background-color: {{VALUE}} !important;',
				],
			]
		);		
		
		$this->add_control(
            'gallery_dots_size_active',
            [
                'label' => __( 'Dot Size', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-gallery-images .slick-dots li.slick-active button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_control(
			'gallery_zoom_color',
			[
				'label' 		=> __( 'Zoom Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .lightbox-btn' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'gallery_zoom_color_hover',
			[
				'label' 		=> __( 'Zoom Hover Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .lightbox-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'gallery_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images .woocommerce-product-gallery__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'gallery_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .images .woocommerce-product-gallery__wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_control(
            'heading_gallery_thumbnails',
            [
                'label' => __( 'Thumbnails', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
		
		$this->add_responsive_control(
            'gallery_thumbnails_width',
            [
                'label' => __( 'Thumbnails Width', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 120,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-gallery-images .thumbnails-slider .slick-slide' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
				
		$this->add_control(
			'gallery_th_arrows_color',
			[
				'label' 		=> __( 'Arrows Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .thumbnails-slider.slick-slider .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);			
		
		$this->add_control(
            'gallery_th_arrows_size',
            [
                'label' => __( 'Arrows Size', 'briefcase-elementor-widgets' ),
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
					'{{WRAPPER}} .bew-gallery-images .thumbnails-slider.slick-slider .slick-arrow' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}} .bew-gallery-images .thumbnails-slider.slick-slider .slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'thumbnail_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-gallery-images .thumbnails-slider .slick-slide.slick-current img',
				
			]
		);
				
		$this->add_control(
			'thumbnails_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .thumbnails-slider .slick-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'thumbnails_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .thumbnails-slider .slick-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'thumbnails_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .thumbnails-slider .slick-slide' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		
		$this->start_controls_section(
			'section_labels_style',
			[
				'label' => __( 'Labels', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_gallery',
				]	
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'gallery_labels_typography',				
				'selector' => '{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span',
			]
		);
		
		$this->add_control(
			'gallery_labels_color',
			[
				'label' 		=> __( 'Labels Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'gallery_label_new_color',
			[
				'label' 		=> __( 'New Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span.new' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'gallery_label_featured_color',
			[
				'label' 		=> __( 'Featured Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span.hot' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'gallery_label_outofstock_color',
			[
				'label' 		=> __( 'Out of Stock Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span.outofstock' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'gallery_label_sale_color',
			[
				'label' 		=> __( 'Sale Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span.onsale' => 'background: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'gallery_label_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'gallery_label_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_control(
			'heading_gallery_label_title_position',
			[
				'label' => __( 'Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_position_top',
			[
				'label' => __( 'Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges' => 'top: {{VALUE}}px; bottom:unset;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_position_bottom',
			[
				'label' => __( 'Bottom', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges' => 'bottom: {{VALUE}}px; top:unset;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_position_left',
			[
				'label' => __( 'Left', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges' => 'left: {{VALUE}}px; right:unset;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_position_right',
			[
				'label' => __( 'Right', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges' => 'right: {{VALUE}}px; left:unset;',
				],				
			]
		);
				
		$this->add_responsive_control(
			'gallery_label_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span' => 'width: {{VALUE}}px;',						
				],
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span' => 'height: {{VALUE}}px; line-height: {{VALUE}}px;',
				],				
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'gallery_label_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-gallery-images .woocommerce-product-gallery .bew-product-badges span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
				
		$this->end_controls_section();
		
	
		$this->start_controls_section(
			'section_category_style',
			[
				'label' => __( 'Category', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_category',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'category_typography',				
				'selector' => '{{WRAPPER}} .bew-category .category',
			]
		);
				
		$this->start_controls_tabs( 'tabs_category_style' );

		$this->start_controls_tab(
			'tab_category_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'category_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-category .category a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'category_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-category .category' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_category_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'category_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-category .category a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'category_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-category .category:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'category_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-category .category:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'category_hover_animation',
			[
				'label' => __( 'Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'category_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-category .category',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'category_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-category .category' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'category_text_shadow',
				'selector' => '{{WRAPPER}} .bew-category .category',
			]
		);
		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_excerpt_style',
			[
				'label' => __( 'Short Description', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_excerpt',
                ]
			]
		);
		
		$this->add_control(
			'excerpt_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',				
				'selector' => '{{WRAPPER}} .bew-excerpt',
			]
		);
		
		$this->add_responsive_control(
			'excerpt_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-excerpt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_description_style',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_description',
                ]
			]
		);
		
		$this->add_control(
			'description_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-description' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',				
				'selector' => '{{WRAPPER}} .bew-description',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_tabs_style',
            [
                'label' => __( 'Tabs', 'briefcase-elementor-widgets' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'product_tabs',
                ]
            ]
        );

        $this->add_control(
            'navigation_width',
            [
                'label' => __( 'Navigation Width', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.bew-woo-tabs-view-vertical .bew-woo-tabs .woocommerce-tabs ul.tabs' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}.bew-woo-tabs-view-vertical .bew-woo-tabs .woocommerce-tabs .panel' => 'width: calc(100% - {{SIZE}}{{UNIT}});',
                ],
                'condition' => [
                    'tab_layout' => 'vertical',
                ],
            ]
        );

        $this->add_responsive_control(
            'tab_padding',
            [
                'label'  => __('Tab Padding','briefcase-elementor-widgets'),
                'type'   => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tab_border_width',
            [
                'label' => __( 'Border Width', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'tab_border_color',
            [
                'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs' => 'background-color: {{VALUE}};',                    
                ],
            ]
        );
		
		$this->add_responsive_control(
			'tab_align',
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
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs' => 'text-align: {{VALUE}};',
				],
				'condition' => [
                    'tab_layout'    => 'horizontal'
                ]
			]
		);

        $this->add_control(
            'heading_title',
            [
                'label' => __( 'Title', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

     		
		$this->start_controls_tabs( 'tabs_tabs_style' );
		
		$this->start_controls_tab(
			'tab_tabs_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
            'tab_color',
            [
                'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li a' => 'color: {{VALUE}};',
                ],                
            ]
        );
		
		$this->add_control(
			'tab_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li a' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_tabs_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		
		$this->add_control(
			'tabs_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'tabs_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li a:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);
		

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_tabs_active',
			[
				'label' => __( 'Active', 'briefcase-elementor-widgets' ),
			]
		);
		
        $this->add_control(
            'tabs_color_active',
            [
                'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li.active a' => 'color: {{VALUE}}; border-color: {{VALUE}}',					
					'{{WRAPPER}}.bew-woo-tabs-view-vertical .bew-woo-tabs .woocommerce-tabs ul.tabs li a:after' => 'background-color: {{VALUE}}',
                ],                
            ]
        );
				
		$this->add_control(
			'tabs_background_color_active',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li.active a' => 'background-color: {{VALUE}};',
				],
				
			]
		);
				
		$this->add_control(
			'tabs_active_border_type',
			[
				'label' => _x( 'Border Type', 'Border Control', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'briefcase-elementor-widgets' ),
					'solid' => _x( 'Solid', 'Border Control', 'briefcase-elementor-widgets' ),
					'double' => _x( 'Double', 'Border Control', 'briefcase-elementor-widgets' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'briefcase-elementor-widgets' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'briefcase-elementor-widgets' ),
					'groove' => _x( 'Groove', 'Border Control', 'briefcase-elementor-widgets' ),
				],
				'selectors' => [
				'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li.active a' => 'border-style: {{VALUE}};', 
				
				],
			]
		);

		$this->add_control(
			'tabs_active_border_width',
			[
			'label' => _x( 'Width', 'Border Control', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::DIMENSIONS,
			'selectors' => [
				'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li.active a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li a' => 'border-top: {{TOP}}{{UNIT}} solid transparent; border-bottom: {{BOTTOM}}{{UNIT}} solid transparent;',
			],
			'condition' => [
				'tabs_active_border_type!' => '',
			],
			]
		);
		

		$this->add_control(
			'tabs_active_border_color',
			[
			'label' => _x( 'Color', 'Border Control', 'briefcase-elementor-widgets' ),
			'type' => Controls_Manager::COLOR,
			'default' => '',
			'selectors' => [
				'{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li.active a' => 'border-color: {{VALUE}};',
			],
			'condition' => [
				'tabs_active_border_type!' => '',
			],
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tab_typography',
                'selector' => '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs li a',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            ]
        );
		
		$this->add_responsive_control(
            'tab_title_padding',
            [
                'label'  => __('Titles Padding','briefcase-elementor-widgets'),
                'type'   => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs ul.tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		
        $this->add_control(
            'heading_content',
            [
                'label' => __( 'Content', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __( 'Color', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs .panel, {{WRAPPER}} .bew-woo-tabs .woocommerce-tabs .panel h2:first-child' => 'color: {{VALUE}};',
                ],
                'scheme' => [
                    'type' => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_3,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .bew-woo-tabs .woocommerce-tabs .panel, {{WRAPPER}} .bew-woo-tabs .woocommerce-tabs .panel h2:first-child',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_cat_title_style',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'category_title',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_title_typography',				
				'selector' => '{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title',
			]
		);
				
		$this->start_controls_tabs( 'tabs_cat_title_style' );

		$this->start_controls_tab(
			'tab_cat_title_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'cat_title_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title, {{WRAPPER}} .bew-cat-title .woocommerce-category-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cat_title_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cat_title_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'cat_title_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title:hover,{{WRAPPER}} .bew-cat-title.cat-title-absolute.show-cat-title-overlay .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cat_title_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title:hover , {{WRAPPER}} .bew-cat-title .woocommerce-category-title:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'cat_title_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title:hover , {{WRAPPER}} .bew-cat-title .woocommerce-category-title:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'cat_title_hover_animation',
			[
				'label' => __( 'Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cat_title_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'cat_title_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'cat_title_text_shadow',
				'selector' => '{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title',
			]
		);
		
		$this->add_responsive_control(
			'cat_title_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'cat_title_margin',
			[
				'label' => __( 'Text Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_responsive_control(	
			'cat_title_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,				
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title , {{WRAPPER}} .bew-cat-title .woocommerce-category-title' => ' height: {{SIZE}}{{UNIT}};',					
				],				
			]
		);
		
		$this->add_control(
			'heading_cat_title_position',
			[
				'label' => __( 'Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'cat_title_absolute' => 'yes',
                ]
				
			]
		);
		
		$this->add_responsive_control(
			'cat_title_position_top',
			[
				'label' => __( 'Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title.cat-title-absolute' => 'top: {{VALUE}}px;',
				],
				'condition' => [
                    'cat_title_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'cat_title_position_bottom',
			[
				'label' => __( 'Bottom', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title.cat-title-absolute' => 'bottom: {{VALUE}}px;',
				],
				'condition' => [
                    'cat_title_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'cat_title_position_left',
			[
				'label' => __( 'Left', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title.cat-title-absolute' => 'left: {{VALUE}}px;',
				],
				'condition' => [
                    'cat_title_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'cat_title_position_right',
			[
				'label' => __( 'Right', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title.cat-title-absolute' => 'right: {{VALUE}}px;',
				],
				'condition' => [
                    'cat_title_absolute' => 'yes',
                ]
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cat_title_count_style',
			[
				'label' => __( 'Count', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'category_title',
					'cat_title_count' => 'yes',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_title_count_typography',				
				'selector' => '{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title .count',
			]
		);
		
		$this->add_control(
			'cat_title_count_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title .count' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cat_title_count_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'cat_title_count_margin',
			[
				'label' => __( 'Text Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-title .woocommerce-loop-category__title .count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_cat_image',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'category_image',
                ]
			]
		);
		
		$this->add_responsive_control(
			'cat_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%', 'px', 'vw' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cat_space',
			[
				'label' => __( 'Max Width', 'briefcase-elementor-widgets' ) . ' (%)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'cat_image_effects' );

		$this->start_controls_tab( 'cat_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'cat_css_filters',
				'selector' => '{{WRAPPER}} .bew-cat-image img',
			]
		);

		$this->add_control(
			'cat_opacity',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'cat_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'cat_css_filters_hover',
				'selector' => '{{WRAPPER}} .bew-product-image:hover img',
			]
		);

		$this->add_control(
			'cat_opacity_hover',
			[
				'label' => __( 'Opacity', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'cat_background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'cat_image_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cat_image_border',
				'label' => __( 'Image Border', 'briefcase-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .bew-cat-image img',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'cat_image_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cat_image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .bew-cat-image img',
			]
		);
		
		$this->add_responsive_control(
			'cat_img_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'cat_img_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cat-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

		$this->end_controls_section();

	}

	public function get_dynamic_fields($flat = false){

	    $fields = array();
		
		// Woocommerce Product.
		$fields[] = array(
			'code' => 'product_image',
			'name' => 'Product Image',
		);
		
		$fields[] = array(
			'code' => 'product_gallery',
			'name' => 'Product Gallery',
		);
		
		$fields[] = array(
			'code' => 'product_title',
			'name' => 'Product Title',
		);
		
		$fields[] = array(
			'code' => 'product_price',
			'name' => 'Product Price',
		);
		$fields[] = array(
			'code' => 'product_add_to_cart',
			'name' => 'Product Add to cart',
		);
				
		$fields[] = array(
			'code' => 'product_meta',
			'name' => 'Product Meta',
		);
		
		$fields[] = array(
			'code' => 'product_excerpt',
			'name' => 'Product Short Description',
		);
		
		$fields[] = array(
			'code' => 'product_description',
			'name' => 'Product Description',
		);
				
		$fields[] = array(
			'code' => 'product_tabs',
			'name' => 'Product Tabs',
		);
		
		$fields[] = array(
			'code' => 'product_comments',
			'name' => 'Product Reviews',
		);
		
		$fields[] = array(
			'code' => 'product_rating',
			'name' => 'Product Rating',
		);
		
		$fields[] = array(
			'code' => 'product_category',
			'name' => 'Product Category',
		);
				
		// Woocommerce Categories.
		
		$fields[] = array(
			'code' => 'category_title',
			'name' => 'Category Title',
		);
		
		$fields[] = array(
			'code' => 'category_image',
			'name' => 'Category Image',
		);
		
		// general.
	    $fields[] = array(
            'code' => 'post_title',
            'name' => 'Page Title',
        );
	    $fields[] = array(
            'code' => 'post_thumbnail',
            'name' => 'Page Image',
        );
		


		if($flat) {
		    $all = array();
			foreach ( $fields as $field ) {
				$all[ $field['code'] ] = $field['name'];
			}
			return $all;
		}

	    return $fields;
    }
	
	/**
	* Helper method to get the version of the currently installed WooCommerce.
	*
	* @since 1.1.0
	* @return string woocommerce version number or null.
	*/
	public static function get_wc_version() {
			return defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;
	}
	
	
	protected function check_product_add_to_cart(){
		
		$settings = $this->get_settings();
					
		global $product;
		global $post;
		
		if(!$product){
            return '';
        }
		
		$product_id  = $product->get_id();	
		
		$post_type = get_post_type($post->ID);	
					
		if( class_exists( 'WooCommerce' ) ) {
			
			switch ( $post_type ) {
			case 'product':	
			if ( is_single($product_id ) ){			
			
			return woocommerce_template_single_add_to_cart();
			}else {
				
			if ( class_exists( 'Woo_Variation_Swatches_Pro' ) ){	
				// options for wvs_pro plugin
				$position = trim( woo_variation_swatches()->get_option( 'archive_swatches_position' ) );
				if ( $position == "before" ){
					?>
					<div class="woo-variation-swatches-shop-before">
					<?php
						do_action('wvs_pro_variation_show_archive_variation_before_cart_button');
					?>
					</div>
					<?php
						woocommerce_template_loop_add_to_cart();
				}else {
						woocommerce_template_loop_add_to_cart();
					?>
					<div class="woo-variation-swatches-shop-after">
					<?php
						do_action('wvs_pro_variation_show_archive_variation_after_cart_button');
					?>
					</div>
					<?php
				}
			} else{
				return woocommerce_template_loop_add_to_cart();	
			}
			}
			break;
			case 'elementor_library':	
			
			$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
			
				if ( 'woo-product' == $bew_template_type  ){
								
					return woocommerce_template_single_add_to_cart();				 					 			
				}else {
				
					return woocommerce_template_loop_add_to_cart();		
				}		    
			break;
			default:
			
			return woocommerce_template_loop_add_to_cart();	    
			}
		}
	}
			
	protected function get_product_data_by_id(){
		
	$settings = $this->get_settings();
		
		$product_type 		 			 		= $settings['product_type'];
		$product_id_custom 	 					= $settings['product_id'];		
		$product_show_qty_box 	 			 	= $settings['product_show_qty_box'];
		$product_addtocart_icon 		    	= $settings['product_addtocart_icon'];
		$product_addtocart_text 		    	= $settings['product_addtocart_text'];
		$product_addtocart_icon_align 		    = $settings['product_addtocart_icon_align'];
		$product_buttom_direction				= $settings['product_buttom_direction'];
		$product_addtocart_underlines			= $settings['product_addtocart_underlines'];
		
		// Wrap classes
		$wrap_classes 		= array( 'product-by-id');
		
		if ( 'yes' == $product_show_qty_box) {
				$wrap_classes[]  = 'show-qty';
		}		
				
		$wrap_classes = implode( ' ', $wrap_classes );
		
		// Inner classes
		$inner_classes 		= array( 'bew-add-to-cart');
		
		if ( 'yes' == $product_type) {
				$inner_classes[]  = 'button-by-id';
		}
		
		if ( 'yes' == $product_show_qty_box) {
				$inner_classes[]  = 'show-qty';
		}
		
		if ( '' == $product_addtocart_text ) {
				$inner_classes[]  = 'button-no-text';
		}		
				$inner_classes[]  = 'bew-align-icon-' . $product_addtocart_icon_align;
				
				$inner_classes[]  = 'bew-product-button-' . $product_buttom_direction;
				
		if ( 'yes' == $product_addtocart_underlines) {
				$inner_classes[]  = 'btn-underlines';
		
		}		
						
		$inner_classes = implode( ' ', $inner_classes );		
		
	// Show custom ID		
			 
            if ($product_id_custom != ''):
                $product_data = wc_get_product($product_id_custom);
            else:
             // Todo:: Get product from template meta field if available
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $preview_data = get_posts( $args );
                $product_data =  wc_get_product($preview_data[0]->ID);
				
				
            endif;
        
        $product = $product_data; 
				
	// Add to cart underlines mode		
			if ( 'yes' == $product_addtocart_underlines) {
					$svg  =	'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="61" height="12" viewBox="0 0 61 12"><path d="';
					$html = 'M60.217,1.433 C45.717,2.825 31.217,4.217 16.717,5.609 C13.227,5.944 8.806,6.200 6.390,5.310 C7.803,4.196 11.676,3.654 15.204,3.216 C28.324,1.587 42.033,-0.069 56.184,0.335 C58.234,0.394 60.964,0.830 60.217,1.433 ZM50.155,5.670 C52.205,5.728 54.936,6.165 54.188,6.767 C39.688,8.160 25.188,9.552 10.688,10.943 C7.198,11.278 2.778,11.535 0.362,10.645 C1.774,9.531 5.647,8.988 9.175,8.551 C22.295,6.922 36.005,5.265 50.155,5.670 Z';
					$svg2 = '"></path></svg>';
					}	
		
	// Made the add to cart buttom by id		
		echo'<div class="'. esc_attr( $wrap_classes ) .'">';
		
			// Quantity section
			if ( ! is_shop() && ! is_product_taxonomy() && $product->is_type( 'simple' ) ) {
			echo'<div id="bew-qty" class="bew-quantity">';		
			$quantity_field = woocommerce_quantity_input( array(
				'input_name'  => 'product_id',
				'input_value' => ! empty( $product->cart_item['quantity'] ) ? $product->cart_item['quantity'] : 1,
				'max_value'   => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
				'min_value'   => 0,
			), $product, false );
			echo $quantity_field;
			echo '</div>';		
			}

		   // Buttom section
		   if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
					$product_id = $product->get_id();
					$product_type = $product->get_type();
				} else {
					$product_id = $product->id;
					$product_type = $product->product_type;
				}

				$class = implode( ' ', array_filter( [
					'button bew-element-woo-add-to-cart-btn',
					'product_type_' . $product_type,
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : 'out-of-stock',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
				] ) );

			echo'<div id="bew-cart" class="'. esc_attr( $inner_classes ) .'">';	
					
				if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
						
				echo apply_filters( 'woocommerce_loop_add_to_cart_link',
				   sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s"><i class="%s" aria-hidden="true"></i>%s'.$svg. '%s' . $svg2 .'</a>',			   
				   esc_url( $product->add_to_cart_url() ),
					   esc_attr( isset( $quantity ) ? $quantity : 1 ),
					   esc_attr( $product->get_id() ),
					   esc_attr( $product->get_sku() ),
					   esc_attr( isset( $class ) ? $class : 'button bew-element-woo-add-to-cart-btn' ),
					   esc_attr( $product_addtocart_icon ),
					   esc_html( $product_addtocart_text ),
					   esc_attr( $html )
				   ),
				   $product );			
			   } elseif( $product && $product->is_type( 'variable' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
				  woocommerce_variable_add_to_cart(); 
			   }
		   
		   echo '</div>';
		   
		echo '</div>';   
	   
	// JS for update the quantity data
	wc_enqueue_js( "
	jQuery( '#bew-qty .qty' ).on( 'change', function() {
		
		var qty = jQuery( this ),
			atc = jQuery( this ).on( '#bew-cart a' );
			
			jQuery( '#bew-cart .add_to_cart_button' ).attr( 'data-quantity', qty.val() );
	});
" );
		
	}
	
	protected function product_add_to_cart_visible_by_id(){	
		
	$settings = $this->get_settings();
	
	$product_type 					= $settings['product_type'];
	$product_showqtyv				= $settings['product_showqtyv'];
	$product_id_custom 				= $settings['product_id'];
	$product_addtocart_icon    		= $settings['product_addtocart_icon'];
	$product_addtocart_text    		= $settings['product_addtocart_text'];
	$product_addtocart_icon_align 	= $settings['product_addtocart_icon_align'];	
	
	
	// show qty box
		if ( 'yes' == $product_showqtyv ) {
						$show_qty_class = 'show-qty';
		}
	// Inner classes
		$inner_classes 		= array( 'bew-add-to-cart-avm');
		
		if ( 'yes' == $product_type) {
				$inner_classes[]  = 'button-by-id';
		}
		
		if ( '' == $product_addtocart_text ) {
				$inner_classes[]  = 'button-no-text';
		}
				$inner_classes[]  = 'bew-align-icon-' . $product_addtocart_icon_align;
		
		$inner_classes = implode( ' ', $inner_classes );
		
	// Show custom ID			 
		if ( 'yes' == $product_type) { 
            if ($product_id_custom != ''):
                $product_data = wc_get_product($product_id_custom);
            else:
             // Todo:: Get product from template meta field if available
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $preview_data = get_posts( $args );
                $product_data =  wc_get_product($preview_data[0]->ID);
            endif;        
			$product = $product_data; 
		} else {	
		// Show current product data		
        global $product; 
		}
		
		// Made the add to cart buttom by id		
		
		echo '<div id="top-avm" class = "always-visible productadd">';
		echo '<div class = "product-title">';		
		echo '<h2 class="single-post-title-ovm" itemprop="name">' . $product->get_title() . '</h2>';
		echo '</div>';
		echo '<div class = "product-buttom">';	
		echo '<div class = "product-by-id ' . $show_qty_class . '">';	
		
			// Quantity section
			if ( ! is_shop() && ! is_product_taxonomy() ) {
			echo'<div id="bew-qty-avm" class="bew-quantity">';		
			$quantity_field = woocommerce_quantity_input( array(
				'input_name'  => 'product_id',
				'input_value' => ! empty( $product->cart_item['quantity'] ) ? $product->cart_item['quantity'] : 1,
				'max_value'   => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
				'min_value'   => 0,
			), $product, false );
			echo $quantity_field;
			echo '</div>';		
			}

		   // Buttom section
		   if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
					$product_id = $product->get_id();
					$product_type = $product->get_type();
				} else {
					$product_id = $product->id;
					$product_type = $product->product_type;
				}

				$class = implode( ' ', array_filter( [
					'button bew-element-woo-add-to-cart-btn',
					'product_type_' . $product_type,
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : 'out-of-stock',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
				] ) );

		   
		   if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
			
			echo'<div id="bew-cart-avm" class="'. esc_attr( $inner_classes ) .'">';	
			echo apply_filters( 'woocommerce_loop_add_to_cart_link',
			   
			   sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s"><i class="%s" aria-hidden="true"></i>%s</a>',
			   esc_url( $product->add_to_cart_url() ),
				   esc_attr( isset( $quantity ) ? $quantity : 1 ),
				   esc_attr( $product->get_id() ),
				   esc_attr( $product->get_sku() ),
				   esc_attr( isset( $class ) ? $class : 'button bew-element-woo-add-to-cart-btn' ),
				   esc_attr( $product_addtocart_icon ),
				   esc_html( $product_addtocart_text )	
			   ),
			   $product );
			echo '</div>';
		   }
		   
		echo '</div>';
		echo '<p itemprop="price">' . $product->get_price_html() . '</p>';		
		echo '</div>';
		echo '</div>';
	   
	// JS for update the quantity data
	wc_enqueue_js( "
	jQuery( '#bew-qty .qty' ).on( 'change', function() {
		
		var qty = jQuery( this ),
			atc = jQuery( this ).on( '#bew-cart a' );
			
			jQuery( '#bew-cart .add_to_cart_button' ).attr( 'data-quantity', qty.val() );
	});
" );
		
		
	}
	
	protected function product_add_to_cart_visible(){	
		
	$settings = $this->get_settings();
	
	$product_type 			= $settings['product_type'];
	$product_showqtyv		= $settings['product_showqtyv'];
	$product_id_custom 		= $settings['product_id'];
	$product_addtocart_text = $settings['product_addtocart_text'];
	
	// show qty box
		if ( 'yes' == $product_showqtyv ) {
						$show_qty_class = 'show-qty';
		}
	
		// Show custom ID			 
		if ( 'yes' == $product_type) { 
            if ($product_id_custom != ''):
                $product_data = wc_get_product($product_id_custom);
            else:
             // Todo:: Get product from template meta field if available
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => 1
                );
                $preview_data = get_posts( $args );
                $product_data =  wc_get_product($preview_data[0]->ID);
            endif;        
			$product = $product_data; 
		} else {	
		// Show current product data		
        global $product; 
		}
			
		if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
		$html  = '<div id="top-avm" class = "always-visible ' . $show_qty_class . ' productadd ">';
		$html .= '<div class = "product-title">';		
		$html .= '<h2 class="single-post-title-ovm" itemprop="name">' . $product->get_title() . '</h2>';
		$html .= '</div>';
		$html .= '<div class = "product-buttom">';		
		$html .= '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= woocommerce_quantity_input( array(
				'input_name'  => 'product_id',
				'input_value' => ! empty( $product->cart_item['quantity'] ) ? $product->cart_item['quantity'] : 1,
				'max_value'   => $product->backorders_allowed() ? '' : $product->get_stock_quantity(),
				'min_value'   => 0,
			), $product, false );
		$html .= '<button type="submit" name="add-to-cart" value="'.$product->get_id().'" class="single_add_to_cart_button button button-by-id alt ">' . $product_addtocart_text . '<span class = "line"> - </span>' . $product->get_price_html() . '</button>';
		$html .= '</form>';
		$html .= '<p itemprop="price">' . $product->get_price_html() . '</p>';
		$html .= '</div>';
		$html .= '</div>';
		}
		
		echo $html;
		
		do_action( 'woocommerce_before_single_product' );
	}
	
	 protected function bew_loop_add_to_cart($product_addtocart_icon,$product_addtocart_text, $overlay_button, $product_addtocart_underlines){
		
	   global $product;
	   
	   if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
				$product_id = $product->get_id();
				$product_type = $product->get_type();
			} else {
				$product_id = $product->id;
				$product_type = $product->product_type;
			}

			$class = implode( ' ', array_filter( [
				'button bew-element-woo-add-to-cart-btn',
				'product_type_' . $product_type,
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : 'out-of-stock',
				$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
			] ) );

			if ( 'square' == $overlay_button || 'circle' == $overlay_button ) {
				if ( '' == $product_addtocart_icon) {
				$product_addtocart_icon = 'fa fa-shopping-bag';}
				$product_addtocart_text = '';				
			}
			if ( 'yes' == $product_addtocart_underlines) {
			$svg  =	'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="61" height="12" viewBox="0 0 61 12"><path d="';
			$html = 'M60.217,1.433 C45.717,2.825 31.217,4.217 16.717,5.609 C13.227,5.944 8.806,6.200 6.390,5.310 C7.803,4.196 11.676,3.654 15.204,3.216 C28.324,1.587 42.033,-0.069 56.184,0.335 C58.234,0.394 60.964,0.830 60.217,1.433 ZM50.155,5.670 C52.205,5.728 54.936,6.165 54.188,6.767 C39.688,8.160 25.188,9.552 10.688,10.943 C7.198,11.278 2.778,11.535 0.362,10.645 C1.774,9.531 5.647,8.988 9.175,8.551 C22.295,6.922 36.005,5.265 50.155,5.670 Z';
            $svg2 = '"></path></svg>';
			}
			
						
			if ($product->is_in_stock()) {
				
				if (empty($product_addtocart_text)) {
				$product_addtocart_text_v = '';
				} else {
						
					$product_type = $product->get_type();
					$external_text = $product->button_text;
			
					$subscription_text = get_option( 'woocommerce_subscriptions_add_to_cart_button_text', __( 'Sign Up Now', 'woocommerce-subscriptions' ) );
			
			
					switch ( $product_type ) {
						case 'simple':
							$options = $product_addtocart_text;
						break;
						case 'grouped':
							$options = 'View products';
						break;
						case 'external':						
							if (empty($external_text)){
								$options = 'Buy Now';	
							} else{
								$options = $external_text;							
							}
						break;
						case 'variable':
							$options = 'Select options';
						break;
						case 'subscription':							
							if (empty($subscription_text)){
								$options = 'Sign Up Now';	
							} else{
								$options = $subscription_text;							
							}
						break;
						case 'variable-subscription':
							if (empty($subscription_text)){
								$options = 'Select options';	
							} else{
								$options = $subscription_text;							
							}
						break;
						case 'booking':
							$options = 'Book now';
						break;
						default:
							$options = $product_addtocart_text;
					}
											
					$product_addtocart_text_v = $options;				
				} 
			} else {
				if (empty($product_addtocart_text)) {
				$product_addtocart_text_v = '';
				} else {
				$product_addtocart_text_v = 'Read More';
				}
			}

       echo apply_filters( 'woocommerce_loop_add_to_cart_link',
           sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s"><i class="%s" aria-hidden="true"></i>%s'.$svg. '%s' . $svg2 .'</a>',
               esc_url( $product->add_to_cart_url() ),
               esc_attr( isset( $quantity ) ? $quantity : 1 ),
               esc_attr( $product->get_id() ),
               esc_attr( $product->get_sku() ),
               esc_attr( isset( $class ) ? $class : 'button bew-element-woo-add-to-cart-btn' ),
			   esc_attr( $product_addtocart_icon ),
               esc_html( $product_addtocart_text_v ),
			   esc_attr( $html )
           ),
           $product );
	 }
	 
	 /**
 * Changes the external product button's add to cart text
 *
 * @param string $button_text the button's text
 * @param \WC_Product $product
 * @return string - updated button text
 */
function sv_wc_external_product_button( $button_text) {
	
	global $product;   
	
    if ( 'external' == $product->get_type() ) {
        // enter the default text for external products
		
        return $product->button_text ? $product->button_text : 'Buy at Amazon';
		
    }
	
    return $button_text;
}

	
	protected function product_add_to_cart_html(){	
			
		
		$settings = $this->get_settings();
		$dynamic_field_value 					= $settings['dynamic_field_value'];
		$product_show_qty_box 	 			 	= $settings['product_show_qty_box'];
		$product_show_qty_text 	 			 	= $settings['product_show_qty_text'];
		$product_qty_text 	 			 	    = $settings['product_qty_text'];
		$product_addtocart_visible_buttom		= $settings['product_addtocart_visible_buttom'];
		$product_addtocart_hover_buttom 		= $settings['product_addtocart_hover_buttom'];
		$product_addtocart_icon 		    	= $settings['product_addtocart_icon'];
		$product_addtocart_text 		    	= $settings['product_addtocart_text'];
		$product_addtocart_icon_align 		    = $settings['product_addtocart_icon_align'];
		$product_buttom_direction				= $settings['product_buttom_direction'];
		$overlay_button							= $settings['overlay_button'];
		$product_addtocart_underlines			= $settings['product_addtocart_underlines'];
		$price_variation_text					= $settings['price_variation_text'];
		$product_addtocart_vp_dynamic		    = $settings['product_addtocart_variation_price_dynamic'];
		
		global $product;
		// Inner classes
		$inner_classes 		= array( 'bew-add-to-cart bew-add-to-cart-single');
		
		if ( 'yes' == $product_show_qty_box) {
				$inner_classes[]  = 'show-qty';
		}
		if ( 'yes' == $product_addtocart_visible_buttom) {
				$inner_classes[]  = 'hide-buttomdc';
		}
		if ( 'yes' == $product_addtocart_hover_buttom) {
				$inner_classes[]  = 'hover-buttom';
		}
		if ( 'custom' == $overlay_button) {
				$inner_classes[]  = 'btn-custom';
		}
		if ( 'square' == $overlay_button) {
				$inner_classes[]  = 'btn-square';
				
		}
		if ( 'circle' == $overlay_button) {
				$inner_classes[]  = 'btn-circle';
		}
		if ( 'yes' == $product_addtocart_underlines) {
				$inner_classes[]  = 'btn-underlines';		
		}
				
		if ( 'block' == $product_buttom_direction) {
				$inner_classes[]  = 'bew-cart-vertical';
		}
		$inner_classes[] = 'bew-image-'. $product->get_id();
		$inner_classes[] = 'bew-product-'. $product->get_id();
						
		if ( '' == $product_addtocart_text || 'circle' == $overlay_button || 'square' == $overlay_button) {
		$inner_classes[]  = 'button-no-text';
		$inner_classes[]  = 'bew-align-icon-middle';
		} else {
		$inner_classes[]  = 'bew-align-icon-' . $product_addtocart_icon_align;	
		}
		$inner_classes = implode( ' ', $inner_classes );
		
		ob_start();
		
		?>		
		<div id="bew-cart" class="<?php echo esc_attr( $inner_classes ); ?>">		
		<?php
		if ( 'square' == $overlay_button || 'circle' == $overlay_button  || 'yes' == $product_addtocart_underlines || ('' != $product_addtocart_icon && !is_product()) ) {					
		$this->bew_loop_add_to_cart($product_addtocart_icon,$product_addtocart_text,$overlay_button,$product_addtocart_underlines);	
		}
		else {
		
		$product_type = $product->get_type();
		
		$product_addtocart_text_sanitize = sanitize_title($product_addtocart_text);
		
		if ('add-to-cart' != $product_addtocart_text_sanitize) {	
		// filter add to cart text
		add_filter( 'woocommerce_product_add_to_cart_text' , [ $this, 'custom_woocommerce_product_add_to_cart_text' ] );
		add_filter( 'woocommerce_product_single_add_to_cart_text', [ $this,  'custom_woocommerce_product_add_to_cart_text' ] );
		add_filter( 'woocommerce_booking_single_add_to_cart_text', [ $this, 'custom_woocommerce_product_add_to_cart_text' ] );	
		
		$this->custom_woocommerce_product_add_to_cart_text();
		
		}
		
		// Apply the correct add to cart type
		$this->check_product_add_to_cart();
					
		// Change the add to cart buttom text with java
		$product_type = $product->get_type();	
				
		if (is_product()) {	
		$single = 'yes';
		}else {
		$single = '';	
		}		
		$product_id  = $product->get_id();	
		$current_single = is_single($product_id );
		
		// Show Dynamic Variation Price.
		
		if($product_addtocart_vp_dynamic == 'yes' && $current_single){
			?>
			<script type="text/javascript">
				jQuery(function ($) {
				/**
				* Change Price Variation to correct position
				*/				
					$('.variations_form').on('show_variation', function () {
						
						var variation_price = $('.woocommerce-variation-price .price').html();
										
						if(variation_price){
							$('.woocommerce-variation' ).addClass("no-margin");
							$('.woocommerce-variation-description' ).css("margin","0");	
							$('.bew-price-grid .product-price' ).hide();
							$('.bew-price-grid .bew-variation-price .price').html(variation_price).show();
						}					
					});				
				});	
						
				jQuery(function ($) {
					$('.variations_form').on('hide_variation', function () {	
						$('.bew-price-grid .product-price' ).show();
					  $('.bew-price-grid .bew-variation-price .price').hide();						 
					});						
				});	
						
			</script>
			<?php
		}
						
		// js for add icon to the buttom on product page
		$icon_type = '<i class="' . $product_addtocart_icon . '" aria-hidden="true"></i>';
		?>
		<script type="text/javascript">
		( function( $ ) {
			$(document).ready(function() {

			
			var product_id = '.bew-product-<?php echo $product->get_id(); ?>'
			var submit_button = $('#bew-cart' + product_id  + ' .button');				
			var icon_type = '<?php echo $icon_type; ?>';
			var icon = '<?php echo $product_addtocart_icon; ?>';
			var text_button = '<?php echo $product_addtocart_text; ?>';
			var ptype = '<?php echo $product_type; ?>';
			var single = '<?php echo $single; ?>';
			var current_single = '<?php echo $current_single; ?>';
			var price_variation_text = '<?php echo $price_variation_text; ?>';
			var price_variation = $('.single_variation_wrap');
			var product_qty_text = '<?php echo $product_qty_text; ?>';
			var product_qty = $('#bew-cart .quantity');
			
			if (icon === '') {				
			} else {	
			submit_button.not(':has(i)').prepend(icon_type);	
			}
			if (price_variation_text === '') {				
			} else {						
			  price_variation.prepend('<style>#bew-cart .woocommerce-variation-price .price:before{ content: "' + price_variation_text + ': "}</style>');
			}
			
			if (product_qty_text === '') {				
			} else {						
			  product_qty.before('<span class="bew-qty-text">' + product_qty_text + '</span>');
			}
			
			if (single === 'yes'&& current_single === '' && ptype === 'variable' && icon === '') {
			submit_button.text('Select options');			
			}
			if (single === 'yes' && current_single === '' && ptype === 'grouped' && icon === '') {
			submit_button.text('View products');			
			}
			if (single === 'yes' && current_single === '' && ptype === 'variable-subscription' && icon === '') {
			submit_button.text('Select options');			
			}
						
			});
		} )( jQuery );		
		</script>
		
		<?php	
		}
		?>
		</div>
		<?php
		$my_buttom = ob_get_clean();
		
		echo $my_buttom; 
		
	
	}
	
	
function custom_woocommerce_product_add_to_cart_text() {
	
		$settings = $this->get_settings();
		
		global $product, $post;
		
		$product_addtocart_text    	= $settings['product_addtocart_text'];
		
		$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
		
		
		if (empty($product_addtocart_text)) {
			
				return __( $options = '', 'woocommerce' );
				
		} else {					
		
			$external_text = $product->button_text;
			
			$subscription_text = get_option( 'woocommerce_subscriptions_add_to_cart_button_text', __( 'Sign Up Now', 'woocommerce-subscriptions' ) );
			
			$product_type = $product->get_type();
			
			if ( $product->is_in_stock()) {
			
				if (is_product() || (Elementor\Plugin::instance()->editor->is_edit_mode() && 'woo-product' == $bew_template_type) ) {
					
					switch ( $product_type ) {
						case 'simple':
							return __( $options = $product_addtocart_text, 'woocommerce' );
						break;
						case 'grouped':
							return __( $options = $product_addtocart_text, 'woocommerce' );
						break;
						case 'external':						
							if (empty($external_text)){
								return __( $options = 'Buy Now', 'woocommerce' );	
							} else{
								return __( $options = $external_text, 'woocommerce' );							
							}
						break;
						case 'variable':
							return __( $options = $product_addtocart_text, 'woocommerce' );
						break;
						case 'subscription':							
							if (empty($subscription_text)){
								return __( $options = 'Sign Up Now', 'woocommerce' );	
							} else{
								return __( $options = $subscription_text, 'woocommerce' );							
							}
						break;
						case 'variable-subscription':
							if (empty($subscription_text)){
								return __( $options = 'Sign Up Now', 'woocommerce' );	
							} else{
								return __( $options = $subscription_text, 'woocommerce' );							
							}
						break;
						case 'booking':
							return __( $options = 'Book now', 'woocommerce-bookings' );
						break;
						default:
							return __( $options = $product_addtocart_text, 'woocommerce' );
					} 
				}	
				else {

					switch ( $product_type ) {
						case 'simple':
							return __( $options = $product_addtocart_text, 'woocommerce' );
						break;
						case 'grouped':
							return __( $options = 'View products', 'woocommerce' );
						break;
						case 'external':
							if (empty($external_text)){
								return __( $options = 'Buy Now', 'woocommerce' );	
							} else{
								return __( $options = $external_text, 'woocommerce' );							
							}
						break;
						case 'variable':
							return __( $options = 'Select options', 'woocommerce' );
						break;
						break;
						case 'subscription':
							if (empty($subscription_text)){
								return __( $options = 'Sign Up Now', 'woocommerce' );	
							} else{
								return __( $options = $subscription_text, 'woocommerce' );							
							}
						break;
						case 'variable-subscription':
							return __( $options = 'Select options', 'woocommerce' );
						break;
						case 'booking':
							return __( $options = 'Book now', 'woocommerce-bookings' );
						break;
						default:
							return __( $options = $product_addtocart_text, 'woocommerce' );
					}
				}
			} else {
				
				return __( $options = 'Read more', 'woocommerce' );					
			}		
				
			}
			
    }
		
	protected function dynamic_field_checker(){
		
	$settings = $this->get_settings();
		
	$dynamic_field_value = $settings['dynamic_field_value'];	
	
	$callback = false;
				$available_callbacks = $this->get_dynamic_fields(true);
				if( $settings && !empty($dynamic_field_value) ){
					$callback = '{{'.$dynamic_field_value.'}}';
				}
				if( $settings && !empty($settings['dynamic_html']) ){
					$callback = $settings['dynamic_html'];
				}
				if($callback) {	        
					require_once BEW_PATH . '/widgets/class.dynamic-field.php';
					$dyno_generator = \BewDynamicField::get_instance();

					if( preg_match_all('#\{\{([a-z_]+)\}\}#imsU', $callback, $matches)){
						foreach($matches[1] as $key=>$field){
							if( isset($available_callbacks[$field])){
								$replace = $dyno_generator->$field();
								$callback = str_replace('{{' . $field . '}}', $replace, $callback);
							}
						}
					}
				}
				
				echo $callback;	
	}
	/**
	 * Custom Add to Cart labels
	 *
	 * @since 1.1.0
	 */

	
	
	/**
	 * Check if product is in stock
	 *
	 * @since 1.0.0
	 */
	public static function bew_woo_product_instock( $product_id = '' ) {
			global $product;
			$product_id      = $product_id ? $product_id : $product->get_id();
			$stock_status 	 = get_post_meta( $product_id, '_stock_status', true );
			if ( 'instock'  != $stock_status ) {
				return true;
			} else {
				return false;
			}
		}

	/**
	 * Adds an out of stock tag to the products.
	 *
	 * @since 1.0.0
	 */
	public static function add_out_of_stock_badge_bew() {
	
	global $product;
			$product_id      = $product_id ? $product_id : $product->get_id();
			$stock_status 	 = get_post_meta( $product_id, '_stock_status', true );
			if ( 'instock'  != $stock_status ) {
				$label = esc_html__( 'Out of Stock', 'briefcase-elementor-widgets' );  ?>
				<div class="outofstock-badge">
					<?php echo esc_html( apply_filters( 'bew_woo_outofstock_text', $label ) ); ?>
				</div><!-- .product-entry-out-of-stock-badge -->
			
		<?php }
	}

	/**
	 * Returns our product thumbnail from our template parts based on selected style in theme mods.
	 *
	 * @since 1.0.0
	 */
	public static function loop_product_thumbnail_bew($product_image_style, $product_image_size, $product_image_style_slider_thumbnails, $product_image_slider_layout) {
		if ( function_exists( 'wc_get_template' ) ) {
			global $product;
			
			$slider_thumbnails = false;
			
			if ((isset($product_image_style_slider_thumbnails) ? $product_image_style_slider_thumbnails : null) === 'yes') { 
				$slider_thumbnails = true;				
			}
			
			// Get entry product media template part
			wc_get_template( 'loop/thumbnail/'. $product_image_style .'.php' ,
							array('product_image_size' => $product_image_size,
								  'slider_thumbnails' => $slider_thumbnails,
								  'product_image_slider_layout' => $product_image_slider_layout) );
		}
	}
	
	/**
		 * Quick view button.
		 *
		 * @since 1.4.2
		 */
	public static function quick_view_button() {
		global $product;

		$button  = '<a href="#" id="product_id_' . $product->get_id() . '" class="owp-quick-view" data-product_id="' . $product->get_id() . '"><i class="icon-eye"></i>' . esc_html__( 'Quick View', 'oceanwp' ) . '</a>';
		echo apply_filters( 'bew_woo_quick_view_button_html', $button );
	}
		
	function get_woo_registered_tabs($output = ''){
       $registered_tabs = [];
		
       $tabs = apply_filters( 'woocommerce_product_tabs', array() );

       if($output == 'full'){
			
           return $tabs;
       }

       foreach($tabs as $tab_key => $tab){
           $registered_tabs[$tab_key] = $tab['title'];
       }

       return $registered_tabs;
	}
	
	/**
	 * Checks if tab is valid for this product
	 * @param $tab
	 * @param $registered_tab
	 * @return bool
	 */
	function is_tab_valid($tab,$registered_tabs){

		if($tab['tab_type'] == 'custom' || in_array($tab['tab_type'],array_keys($registered_tabs))){
			return true;
		}

		return false;
	}
	
	/**
	 * Create slug	 
	 */	
	public static function createSlug($str, $delimiter = '_'){

	$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
	return $slug;

	}


	/**
	 * Load comments template.
	 *
	 * @param string $template template to load.
	 * @return string
	 */
	public static function comments_template_loader( $template ) {
		if ( get_post_type() !== 'product' ) {
			return $template;
		}

			$check_dirs = array(
				trailingslashit( BEW_PATH ) . WC()->template_path(),				
				trailingslashit( BEW_PATH ),
			);

			if ( WC_TEMPLATE_DEBUG_MODE ) {
				$check_dirs = array( array_pop( $check_dirs ) );
			}

			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . 'single-product-reviews.php' ) ) {
					return trailingslashit( $dir ) . 'single-product-reviews.php';
				}
			}
	}
		
	function woo_comment_form_fields( $fields ){
				if( function_exists('is_product') && is_product()  ){
					$comment_field = $fields['comment'];
					unset( $fields['comment'] );
					$fields['comment'] = $comment_field;
				}
				return $fields;
	}

	/**
	 * Subcategory Count Markup
	 *
	 * @param  mixed  $content  Count Markup.
	 * @param  object $category Object of Category.
	 * @return mixed
	 */
	function subcategory_count_markup( $content, $category ) {

		$content = sprintf( // WPCS: XSS OK.
				/* translators: 1: number of products */
				_nx( '<mark class="count">%1$s Product</mark>', '<mark class="count">%1$s Products</mark>', $category->count, 'product categories', 'astra' ),
			number_format_i18n( $category->count )
		);

		return $content;
	}
	
	/**
     * param $title
     * param $id
     * return mixed
     */
    public function bew_shorten_my_product_title( $title, $id ) {
        $settings = $this->get_settings();		
        $pos = 0;
        
                            
                if($settings['product_title_limit_dots'] == "" && $settings['product_title_limit_character'] < strlen($title)){
                    if ($settings['product_title_limit_wordcutter'] == "yes"){
                        $pos = strpos($title, ' ', $settings['product_title_limit_character']);
                        if(!$pos){
                            return $title;
                        }else{
                            return substr( $title, 0, $pos );
                        }
                    }else{
                        return substr( $title, 0, $settings['product_title_limit_character'] );
                    }
                }else if($settings['product_title_limit_dots'] == "yes" && $settings['product_title_limit_character'] < strlen($title)){
                    if ($settings['product_title_limit_wordcutter'] == "yes"){
                        $pos = strpos($title, ' ', $settings['product_title_limit_character']);
                        if(!$pos){
                            return $title;
                        }else{
                            return substr( $title, 0, $pos ).'...';
                        }
                    }else{
                        return substr( $title, 0, $settings['product_title_limit_character'] ).'...';
                    }
                }else{
                    return $title;
                }
           
        
    }
	
	/**
	 * Product variation lowest price
     * param $price
     * param $product_price_low_text     
     */	
	public function bew_variation_price_low_format( $price, $product ) {
		
		$settings = $this->get_settings();
		$product_price_low_text = $settings['product_price_low_text'];
		$allprices = $product->get_variation_prices();
					 
		// Main Price
		$prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
		$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s %2$s', 'woocommerce' ), $product_price_low_text , wc_price( $prices[0] ) ) : wc_price( $prices[0] );
		$price2 = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
		
		
		// Sale Price		
		$arrayprices = $allprices['price'];
		$key = array_search ($prices[0], $arrayprices);
		$arrayregular = $allprices['regular_price'];
		$regularprice = $arrayregular[$key];
		
		$saleprice = $regularprice ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $regularprice ) ) : wc_price( $regularprice );
				
		if ( $price2 !== $saleprice ) {
		$price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
		}
		return $price;		
	}
	
	/**
	 * Product variation highest price
     * param $price
     * param $product_price_high_text     
     */	
	public function bew_variation_price_high_format( $price, $product ) {
		
		$settings = $this->get_settings();
		$product_price_high_text = $settings['product_price_high_text'];		
		$allprices = $product->get_variation_prices();
						
		// Main Price
		$prices = array( $product->get_variation_price( 'max', true ), $product->get_variation_price( 'min', true ) );
		$price = $prices[0] !== $prices[1] ? sprintf( __( '%1$s %2$s', 'woocommerce' ), $product_price_high_text , wc_price( $prices[0] ) ) : wc_price( $prices[0] );
		$price2 = $prices[0] !== $prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );
		
		
		// Sale Price
		
		$arrayprices = $allprices['price'];
		$key = array_search ($prices[0], $arrayprices);
		$arrayregular = $allprices['regular_price'];
		$regularprice = $arrayregular[$key];
						
		$saleprice = $regularprice ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $regularprice ) ) : wc_price( $regularprice );		
		
		if ( $price2 !== $saleprice ) {
		$price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
		}
		return $price;
		
		
	}
	
	/**
	 * Product variation hide price until select
     * param $price   
     */	
	public function bew_variation_price_hide( $price) {		
		$price = '';
		return $price;
	}
	
	/**
	* Get Product Data for Woo Grid Loop template
	*
	* @since 1.0.0
	*/
		public static function product_data_loop() {
			
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
				
		}
		
		/**
		 * Get Category Data for Woo Grid Loop template
		 *
		 * @since 1.0.0
		 */
		public static function category_data_loop() {
			
			if(empty($category)){
			// Get terms and workaround WP bug with parents/pad counts.
				$args = array(					
					'orderby' => 'id',
					'order'      => 'DESC',					
					'hide_empty' => 0,
					'parent'   => 0
					
				);

				$product_categories = get_terms( 'product_cat', $args );
												
				$id_cat =  $product_categories[0]->term_id;				
				
				$category_data = get_term_by( 'id', $id_cat, 'product_cat' ); 
					
				$category = $category_data;

				return $category;
				
			}
			
				
		}
		
	
	/**
	 * Render our custom field onto the page.
	 */
	protected function render() {
		$settings = $this->get_settings();
		
		$dynamic_field_value 					= $settings['dynamic_field_value'];
		$product_type 		 			 		= $settings['product_type'];
		$product_id_custom 	 			 		= $settings['product_id'];
		$product_addtocart_visible_buttom		= $settings['product_addtocart_visible_buttom'];
		$product_addtocart_ajax					= $settings['product_addtocart_ajax'];		
		$product_opencart_ajax					= $settings['product_opencart_ajax'];	
		$product_show_qty_box 	 			 	= $settings['product_show_qty_box'];
		$product_title_limit 	 			 	= $settings['product_title_limit']; 
		$product_title_link 	 			 	= $settings['product_title_link'];
		$product_image_link 	 			 	= $settings['product_image_link'];
		$product_image_size 	 			 	= $settings['product_image_size'];
		$product_image_style					= $settings['product_image_style'];
		$product_image_style_slider_thumbnails	= $settings['product_image_style_slider_thumbnails'];
		$product_image_slider_layout		    = $settings['product_image_slider_layout'];	
		$product_price_absolute 	 			= $settings['product_price_absolute'];
		$product_price_regular 	 				= $settings['product_price_regular'];
		$product_price_sale 	 				= $settings['product_price_sale'];
		$product_price_low 	 					= $settings['product_price_low'];
		$product_price_low_text 	 			= $settings['product_price_low_text'];
		$product_price_high 	 				= $settings['product_price_high'];
		$product_price_high_text 	 			= $settings['product_price_high_text'];
		$product_price_hide 	 				= $settings['product_price_hide'];
		
		$product_add_to_cart_options	 		= $settings['product_add_to_cart_options'];
		
		$cat_title_absolute						= $settings['cat_title_absolute'];
		$cat_title_absolute_translate			= $settings['cat_title_absolute_translate'];
		$cat_title_count						= $settings['cat_title_count'];
		$cat_image_hover_black					= $settings['cat_image_hover_black'];
		
		
		$product_gallery_layout					= $settings['product_gallery_layout'];
		$product_gallery_lightbox				= $settings['product_gallery_lightbox'];
		$product_gallery_zoom				    = $settings['product_gallery_zoom'];
		
		$product_review_slider					= $settings['product_review_slider'];
		
		$product_gallery_labels_new				= $settings['product_gallery_labels_new'];
		$product_gallery_labels_new_text		= $settings['product_gallery_labels_new_text'];
		$product_gallery_labels_new_days		= $settings['product_gallery_labels_new_days'];
		$product_gallery_labels_featured		= $settings['product_gallery_labels_featured'];
		$product_gallery_labels_featured_text	= $settings['product_gallery_labels_featured_text'];
		$product_gallery_labels_outofstock		= $settings['product_gallery_labels_outofstock'];
		$product_gallery_labels_outofstock_text	= $settings['product_gallery_labels_outofstock_text'];
		$product_gallery_labels_sale			= $settings['product_gallery_labels_sale'];
		$product_gallery_labels_sale_text		= $settings['product_gallery_labels_sale_text'];
		$product_gallery_labels_sale_percent	= $settings['product_gallery_labels_sale_percent'];
		$product_gallery_woo_default			= $settings['product_gallery_woo_default'];
		
		$product_image_labels_new				= $settings['product_image_labels_new'];
		$product_image_labels_new_text			= $settings['product_image_labels_new_text'];
		$product_image_labels_new_days			= $settings['product_image_labels_new_days'];
		$product_image_labels_featured			= $settings['product_image_labels_featured'];
		$product_image_labels_featured_text		= $settings['product_image_labels_featured_text'];
		$product_image_labels_outofstock		= $settings['product_image_labels_outofstock'];
		$product_image_labels_outofstock_text	= $settings['product_image_labels_outofstock_text'];
		$product_image_labels_sale				= $settings['product_image_labels_sale'];
		$product_image_labels_sale_text			= $settings['product_image_labels_sale_text'];
		$product_image_labels_sale_percent		= $settings['product_image_labels_sale_percent'];
		
		
		$this->add_render_attribute( 'title', 'class', 'bew-heading-title' );
		$this->add_render_attribute( 'price', 'class', 'bew-heading-price' );		

			// Add to  cart Buttom
			if($dynamic_field_value == 'product_add_to_cart'  ){
									
				// conditional for product by ID
				if('yes' == $product_type){	
					// conditional for  add to  product always visible mode
					if('yes' == $product_addtocart_visible_buttom){	
					$this->product_add_to_cart_visible_by_id();			
					}					
					$this->get_product_data_by_id();					
				} 
				else {
					// Data for Bew Templates
					$this->product_data_loop();
					
					// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
					
					// conditional for  add to cart product always visible mode
					if('yes' == $product_addtocart_visible_buttom){	
					$this->product_add_to_cart_visible();			
					}
					
					$this->product_add_to_cart_html();
															
					$product_id  = $product->get_id();		
					
					if(is_single($product_id )){	
						
						//passing variables to the javascript file
						wp_localize_script('woo-addtocart-ajax', 'bew_add_to_cart_ajax', array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'ajax_active' => $product_addtocart_ajax,
						'opencart_active' => $product_opencart_ajax,
						'is_cart' => is_cart(),
						'cart_url' => apply_filters( 'bew_woocommerce_add_to_cart_redirect', wc_get_cart_url() ),
						'view_cart' => esc_html__( 'View cart', 'briefcase-elementor-widgets' )
						));		
					}
													
				}
			}
			
			elseif($dynamic_field_value == 'product_title'  ){
				
				// Data for Bew Templates
				$this->product_data_loop();
				
				// Data for Elementor Pro Templates option
					global $product,$post;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
				
				if(Elementor\Plugin::instance()->editor->is_edit_mode()){
					$temp_post = $post;
					$post = get_post($product->get_id());		
				}
					
					
				if("yes" == $product_title_limit){
				add_filter( 'the_title', array($this,'bew_shorten_my_product_title'), 10, 2 );
				}
				
				echo '<div class="bew-product-title">'; 
				if('yes' == $product_title_link){					
					echo '<a href="'.esc_url( get_the_permalink()) .'">';
					echo '<h2 class="woocommerce-loop-product__title product_title">' . get_the_title() . '</h2>';
					echo '</a>';
				}else {
					$this->dynamic_field_checker();
				}
				echo '</div>';
				
				if(Elementor\Plugin::instance()->editor->is_edit_mode()){							
				$post = $temp_post;
				}
			}	
			
			elseif($dynamic_field_value == 'product_image'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
				
				// Data for Elementor Pro Templates option					
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
					
				// Wrapper image classes
				$wrap_classes_image = array( 'bew-product-image' , 'image-wrap' );
				
				$wrap_classes_image[] ='bew-image-' . $product->get_id();
						
								
				$wrap_classes_image = implode( ' ', $wrap_classes_image );
				
				// Product Image
				echo '<div class="product-image">';
				
					// Labels										
					wc_get_template( 'loop/bew-sale-flash.php', 
								array('product_image_labels_new' => $product_image_labels_new,
									  'product_image_labels_new_text' => $product_image_labels_new_text,
									  'product_image_labels_new_days' => $product_image_labels_new_days,
									  'product_image_labels_featured' => $product_image_labels_featured,
									  'product_image_labels_featured_text' => $product_image_labels_featured_text,
									  'product_image_labels_outofstock' => $product_image_labels_outofstock,
									  'product_image_labels_outofstock_text' => $product_image_labels_outofstock_text,
									  'product_image_labels_sale' => $product_image_labels_sale,
									  'product_image_labels_sale_text' => $product_image_labels_sale_text,
									  'product_image_labels_sale_percent' => $product_image_labels_sale_percent ) );									  
				
				
					if('yes' == $product_image_link){
						echo '<div class="' . esc_attr( $wrap_classes_image ). '" id="bew-image-' . $product->get_id() .'">';
													
							self::loop_product_thumbnail_bew($product_image_style, $product_image_size, $product_image_style_slider_thumbnails, $product_image_slider_layout);							
							
						echo '</div>';
					} else {
						$this->dynamic_field_checker();		
					}
					
				echo '</div>';
			}
			elseif($dynamic_field_value == 'product_price'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
				
				
				// Wrapper price classes
				$wrap_classes_price = array( 'product-price' );				
				
				if('yes' == $product_price_absolute){
					$wrap_classes_price[] ='price-absolute';
				}
				
				if ($product->is_on_sale()){
					$wrap_classes_price[] ='product-on-sale';
				} else {
					$wrap_classes_price[] ='product-regular';
				}
				
				if('yes' == $product_price_regular){
					
					$wrap_classes_price[] ='show-price-regular';
				}				
				if('yes' == $product_price_sale){
					
					$wrap_classes_price[] ='show-price-sale';
				}				
				$wrap_classes_price = implode( ' ', $wrap_classes_price );
				
				if('yes' == $product_price_low){
				add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'bew_variation_price_low_format' ), 10, 2 );
				add_filter( 'woocommerce_variable_price_html', array( $this, 'bew_variation_price_low_format' ), 10, 2 );
				//add_filter( 'woocommerce_grouped_sale_price_html', array( $this, 'bew_variation_price_low_format' ), 10, 2 );
				//add_filter( 'woocommerce_grouped_price_html', array( $this, 'bew_variation_price_low_format' ), 10, 2 );
				}
				
				if('yes' == $product_price_high){
				add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'bew_variation_price_high_format' ), 10, 2 );
				add_filter( 'woocommerce_variable_price_html', array( $this, 'bew_variation_price_high_format' ), 10, 2 );
				//add_filter( 'woocommerce_grouped_sale_price_html', array( $this, 'bew_variation_price_high_format' ), 10, 2 );
				//add_filter( 'woocommerce_grouped_price_html', array( $this, 'bew_variation_price_high_format' ), 10, 2 );
				}
				
				if('yes' == $product_price_hide){
				add_filter( 'woocommerce_variable_sale_price_html', array( $this, 'bew_variation_price_hide' ), 10, 2 );
				add_filter( 'woocommerce_variable_price_html', array( $this, 'bew_variation_price_hide' ), 10, 2 );
				//add_filter( 'woocommerce_grouped_sale_price_html', array( $this, 'bew_variation_price_hide' ), 10, 2 );
				//add_filter( 'woocommerce_grouped_price_html', array( $this, 'bew_variation_price_hide' ), 10, 2 );	
				}
				
				$product_id  = $product->get_id();	
				$current_single = is_single($product_id );
				
				echo '<div class="bew-price-grid">';
				echo '<div class="'. esc_attr( $wrap_classes_price ) . '">';
				$this->dynamic_field_checker();
				echo '</div>';
				if($current_single &&  $product->is_type( 'variable' )){
				echo '<div class="bew-variation-price">';
				echo '<p class="price"></p>';
				echo '</div>';
				}
				echo '</div>';
				
			}
			elseif($dynamic_field_value == 'product_category'  ){
				
				$this->product_data_loop();
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option					
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
					
				// Category					
				
				
						echo '<div class="bew-category">';
						if ( version_compare( self::get_wc_version(), '2.7', '>=' ) ) {
							echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ', ', '<li class="category">', '</li>' ) );
						} else {
							echo wp_kses_post( $product->get_categories( ', ', '<li class="category">', '</li>' ) );
						}
						echo '</div>';
			}
			elseif($dynamic_field_value == 'product_meta'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
					
				
				// Meta				
						echo '<div class="bew-product-meta">';
						$this->dynamic_field_checker();
						echo '</div>';
			}
			elseif($dynamic_field_value == 'product_gallery'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}				
													
				// Wrapper gallery classes
				$wrap_classes_gallery = array( 'bew-gallery-images' );				
				
				if('yes' == $product_gallery_woo_default && 'yes' == $product_gallery_zoom){
					$wrap_classes_gallery[] ='product-zoom-on';
				}
				
				$wrap_classes_gallery = implode( ' ', $wrap_classes_gallery );
				
				add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
				return array(
						'width'  => 150,
						'height' => 150,
						'crop'   => 0,
					);
				} );
				
				echo '<div class="'. esc_attr( $wrap_classes_gallery ) . '">';
								
				if(class_exists( 'Woo_Variation_Gallery' ) || $product_gallery_woo_default == 'yes'){
				// Use default WooCommerce Gallery
							
				$this->dynamic_field_checker();	
								
				} else{
				remove_theme_support( 'wc-product-gallery-slider' );	
				wc_get_template( 'single-product/bew-product-image.php', 
								array('product_gallery_layout' => $product_gallery_layout, 
									  'product_gallery_lightbox' => $product_gallery_lightbox, 
									  'product_gallery_zoom' => $product_gallery_zoom,
									  'product_gallery_labels_new' => $product_gallery_labels_new,
									  'product_gallery_labels_new_text' => $product_gallery_labels_new_text,
									  'product_gallery_labels_new_days' => $product_gallery_labels_new_days,
									  'product_gallery_labels_featured' => $product_gallery_labels_featured,
									  'product_gallery_labels_featured_text' => $product_gallery_labels_featured_text,
									  'product_gallery_labels_outofstock' => $product_gallery_labels_outofstock,
									  'product_gallery_labels_outofstock_text' => $product_gallery_labels_outofstock_text,
									  'product_gallery_labels_sale' => $product_gallery_labels_sale,
									  'product_gallery_labels_sale_text' => $product_gallery_labels_sale_text,
									  'product_gallery_labels_sale_percent' => $product_gallery_labels_sale_percent ) );
				}
				
				echo '</div>';
				
			}
			elseif($dynamic_field_value == 'product_excerpt'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
					
								
				// Wrapper excerpt classes
				$wrap_classes_excerpt = array( 'bew-excerpt' );				
							
				$wrap_classes_excerpt = implode( ' ', $wrap_classes_excerpt );
				
				echo '<div class="'. esc_attr( $wrap_classes_excerpt ) . '">';			
				
				$this->dynamic_field_checker();
				echo '</div>';
			}
			
			elseif($dynamic_field_value == 'product_description'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
				
				$edit_mode = get_post_meta($product->get_id(),'_elementor_edit_mode','');
				if(isset($edit_mode[0]) && $edit_mode[0] == 'builder'){
					$product_description = '<div class="bew_data elementor elementor-<?php echo $product_id; ?>">';
					$product_description .=  Elementor\Plugin::instance()->frontend->get_builder_content( $product->get_id() );
					$product_description .= '</div>';
				}else{
					$product_description = wpautop($product->get_description());
					if(isset($GLOBALS['wp_embed'])){
						$product_description = $GLOBALS['wp_embed']->autoembed($product_description);
					}
				}
												
				// Wrapper description classes
				$wrap_classes_description = array( 'bew-description' );				
							
				$wrap_classes_description = implode( ' ', $wrap_classes_description );
				
				echo '<div class="'. esc_attr( $wrap_classes_description ) . '">';			
				echo do_shortcode($product_description);
				echo '</div>';
			}
			
			elseif($dynamic_field_value == 'product_rating'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
					
												
				// Wrapper rating classes
				$wrap_classes_rating = array( 'bew-rating' );				
							
				$wrap_classes_rating = implode( ' ', $wrap_classes_rating );
				
				echo '<div class="'. esc_attr( $wrap_classes_rating ) . '">';			
				
				$this->dynamic_field_checker();
				
				$rating_count = $product->get_rating_count();
				if($rating_count == 0 && Elementor\Plugin::instance()->editor->is_edit_mode() ){
					
				$count = 0;
				$rating  = 0;
				$average      = 0;	
				// Html Ranting on editor
				?>				
				<div class="woocommerce-product-rating">
					<div class="star-rating">
					<span style="width:0%">
					<?php
					sprintf( _n( 'Rated %1$s out of 5 based on %2$s customer rating', 'Rated %1$s out of 5 based on %2$s customer ratings', $count, 'woocommerce' ), '<strong class="rating">' . esc_html( $rating ) . '</strong>', '<span class="rating">' . esc_html( $count ) . '</span>' );
					?>	
					</span>
					</div>
					<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $count, 'woocommerce' ), '<span class="count">' . esc_html( $count ) . '</span>' ); ?>)</a>
				
				</div>
				<?php
				}
				echo '</div>';
			}
						
			elseif($dynamic_field_value == 'product_comments'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product,$post;
					
					if(is_string($product)){
						$product = wc_get_product();
					}									
				
				$post = get_post($product->get_id());				
				
				add_filter( 'comments_template', array( $this, 'comments_template_loader' ) );
				
				add_filter( 'comment_form_fields', array( $this, 'woo_comment_form_fields' ), 9 );
				
				// Wrapper review classes
				$wrap_classes_review = array( 'bew-review' );				
							
				$wrap_classes_review = implode( ' ', $wrap_classes_review );
				
				echo '<div class="'. esc_attr( $wrap_classes_review ) . '">';
					
					$this->dynamic_field_checker();
				
				echo '</div>';
				
					if ($product_review_slider == 'yes'){
					// Review slider
					?>
					<script type="text/javascript">
						jQuery(function($) {				
						$( '.commentlist' ).slick( {
						slidesToShow  : 1,
						slidesToScroll: 1,
						dots          : true,				
						} ); 
							
						});		
					</script>	
					<?php
					}
			}
			
			elseif($dynamic_field_value == 'product_tabs'  ){
				
				// Data for Bew Templates
					$this->product_data_loop();
					
				// Data for Elementor Pro Templates option
					global $product,$post;;
					
					if(is_string($product)){
						$product = wc_get_product();
					}
										
				
				$bewglobal = get_post_meta($post->ID, 'briefcase_apply_global', true);
				
				$post = get_post($product->get_id());
				
				add_filter( 'comments_template', array( $this, 'comments_template_loader' ) );
				
				add_filter( 'comment_form_fields', array( $this, 'woo_comment_form_fields' ), 9 );
								
				setup_postdata($product->get_id());
				$registered_tabs = $this->get_woo_registered_tabs('full');
				
				
				$review_count =  ' (' .$product->get_review_count() .')';
				
				if(count($settings['tabs']) && count($registered_tabs)) {
					?>				
					<div class="bew-woo-tabs">
					<div class="woocommerce-tabs wc-tabs-wrapper">
					<ul class="tabs wc-tabs" role="tablist">
					 <?php
					$counter = 1; ?>
											
							<?php foreach ($settings['tabs'] as $tab) :
								if (!$this->is_tab_valid($tab, $registered_tabs)) {
									continue;
								}
								?>
								<li class="<?php echo $this->createSlug($tab['tab_title']); ?>_tab" data-tab="<?php echo $counter; ?>" id="tab-title-<?php echo $this->createSlug($tab['tab_title']); ?>" role="tab" aria-controls="tab-<?php echo $this->createSlug($tab['tab_title']); ?>">
									<?php
									if ($this->createSlug($tab['tab_title']) == 'reviews' && $product->get_review_count() > '0' ){
									?>	
									<a href="#tab-<?php echo $this->createSlug($tab['tab_title']); ?>"><?php echo apply_filters( 'woocommerce_product_' . $this->createSlug($tab['tab_title']) . '_tab_title', esc_html( $tab['tab_title'] ), $this->createSlug($tab['tab_title']) ) . $review_count ; ?></a>
									<?php
									} else {
									?>
									<a href="#tab-<?php echo $this->createSlug($tab['tab_title']); ?>"><?php echo apply_filters( 'woocommerce_product_' . $this->createSlug($tab['tab_title']) . '_tab_title', esc_html( $tab['tab_title'] ), $this->createSlug($tab['tab_title']) ); ?></a>
									<?php
									}
									?>
								</li>
								<?php
							$counter++;	
							endforeach; ?>
					</ul>		
							<?php
							$counter = 1; ?>
							<?php foreach ($settings['tabs'] as $tab) :
								if (!$this->is_tab_valid($tab, $registered_tabs)) {
									continue;
								}
								?>
								<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo $this->createSlug($tab['tab_title']); ?> panel entry-content wc-tab tab-title-<?php echo $this->createSlug($tab['tab_title']); ?>" data-tab="<?php echo $counter; ?>" id="tab-<?php echo $this->createSlug($tab['tab_title']); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo $this->createSlug($tab['tab_title']); ?>">
								<?php 
								if ($tab['tab_type'] == 'custom') {
									echo do_shortcode(wpautop($tab['custom_tab_content']));
									
								} else {
									if ($tab['tab_type'] == 'description' && is_product() && $bewglobal == 'off' ) {									
									echo do_shortcode(wpautop($tab['custom_description_content']));
									} else {	
									call_user_func($registered_tabs[$tab['tab_type']]['callback'], $tab['tab_type'], $registered_tabs[$tab['tab_type']]);
									}
								} 
								?>
								</div> <?php
								$counter++;
							endforeach; ?>
						
					</div>
					</div>
					<?php
				}else{
				echo 'Add your tabs.';
				}
			wp_reset_postdata();
			
			}
			
			elseif($dynamic_field_value == 'category_title'  ){
				
				
				global $bewcategory;
				$category = $bewcategory;				

				if(empty($category)){
				$category = $this->category_data_loop();
				}
												
				// Wrapper category title classes
				$wrap_classes_cat_title = array( 'bew-cat-title' );		

				if('yes' == $cat_title_absolute){
					$wrap_classes_cat_title[] ='cat-title-absolute';
				}
				
				if('yes' == $cat_title_absolute_translate){
					$wrap_classes_cat_title[] ='cat-title-absolute-translate';
				}
				
				if('yes' == $cat_title_count){
					$wrap_classes_cat_title[] ='cat-title-count';
				}
				
				$wrap_classes_cat_title[] = 'bew-cat-image-'. $category->term_id;
							
				$wrap_classes_cat_title = implode( ' ', $wrap_classes_cat_title );
				
				add_filter( 'woocommerce_subcategory_count_html', array( $this, 'subcategory_count_markup' ), 10, 2 );
				
				echo '<div class="'. esc_attr( $wrap_classes_cat_title ) . '">';
				
					
					//show the category title
					if ( is_product_category() ){
					?>
					<h1 class="woocommerce-category-title page-title"><?php woocommerce_page_title(); ?></h1>
					<?php	
					} else { 
					echo '<a href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '">';
						woocommerce_template_loop_category_title( $category );
					echo '</a>';
					}
				
				echo '</div>';
				
				
				if('yes' == $cat_title_absolute_translate){
				?>	
				<script type="text/javascript">
					( function( $ ) {
						$(document).ready(function() {
						
						var image = $('.bew-cat-image');					
						
						// Title overlay image
	
						image.hover(function(e) {			
						$('.' + this.id)[e.type == 'mouseenter' ? 'addClass' : 'removeClass']('show-cat-title-overlay'); 		

						});
												
						});
					} )( jQuery );		
				</script>
				<?php
				}
			}
			
			elseif($dynamic_field_value == 'category_image'  ){
				
				global $bewcategory;
				$category = $bewcategory;
				
				if(empty($category)){
				$category = $this->category_data_loop();
				}
												
				// Wrapper category classes
				$wrap_classes_cat_image = array( 'bew-cat-image' );	

				if('yes' == $cat_image_hover_black){
					$wrap_classes_cat_image[] ='hover-black-overlay';
				}
							
				$wrap_classes_cat_image = implode( ' ', $wrap_classes_cat_image );
				
				echo '<div class="'. esc_attr( $wrap_classes_cat_image ). '" id="bew-cat-image-' . $category->term_id .'">';								
				
					
					//show the category image
					 if ( is_product_category() ){
						global $wp_query;
						$cat = $wp_query->get_queried_object();
						$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
						$image = wp_get_attachment_url( $thumbnail_id );
						if ( $image ) {
							echo '<div class=" woocommerce-category-image ">';
							echo '<img src="' . $image . '" alt="' . $cat->name . '" />';
							echo '</div>';
						}
					} else {
						echo '<a href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '">';
							woocommerce_subcategory_thumbnail( $category );
						echo '</a>';
					}
									
				echo '</div>';
				
				if('yes' == $cat_image_hover_black){
				?>	
				<script type="text/javascript">
					( function( $ ) {
						$(document).ready(function() {
						
						var title = $('.bew-cat-title');
						
												
						
						// Image overlay title
	
						title.hover(function(e) {

						var lastClass = this.className.split(' ').pop();
						
						$('#' + lastClass)[e.type == 'mouseenter' ? 'addClass' : 'removeClass']('show-cat-image-overlay'); 		

						});
												
						});
					} )( jQuery );		
				</script>
				<?php
				}
			}
					
	}

	/**
	 * This is outputted while rending the page.
	 */
	protected function content_template() {
				
	}

}


Plugin::instance()->widgets_manager->register_widget_type( new Bew_Widget_Dynamic_Field() );