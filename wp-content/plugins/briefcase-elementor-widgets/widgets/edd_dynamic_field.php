<?php
/**
 * EDD Dynamic Field
 *
 * @package briefcase elementor widget
 */

namespace Elementor;

use WP_User;

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}

/**
 * Creates our custom Elementor widget
 *
 * Class briefcase elementor widget
 *
 * @package Elementor
 */
class EDD_Widget_Dynamic_Field extends Widget_Base {

	/**
	 * Get Widgets name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'edd_dynamic';
	}

	/**
	 * Get widgets title
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'EDD Dynamic Field', 'briefcase-elementor-widgets' );
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
		return [ ];
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
				
		$this->end_controls_section();		
				
		$this->start_controls_section(
			'section_add_to_cart',
			[
				'label' => __( 'Add to cart', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_add_to_cart',					
				]
			]
		);

		$this->add_control(
			'download_button_type',
			[
				'label' => __( 'Add to cart Type', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __( 'Normal', 'briefcase-elementor-widgets' ),
					'link' => __( 'Link', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
					'dynamic_field_value' => 'download_add_to_cart',						
				],
			]
		);
				
		
		$this->add_control(
			'download_type',
			[
				'label' 		=> __( 'Custom Add to cart by ID', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'dynamic_field_value' => 'download_add_to_cart',					
				]
				
			]
		);
				
		$this->add_control(
			'download_id',
			[
				'label' 		=> __( 'Download ID', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder' 	=> __( 'Your download ID', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'download_type' => 'yes',
					'dynamic_field_value' => 'download_add_to_cart',										
                ]
					
			]
		);
				
		$this->add_control(
			'download_addtocart_text',
			[
				'label' => __( 'Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Add to Cart', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Add to Cart', 'briefcase-elementor-widgets' ),
				'condition' => [                    
					'dynamic_field_value' => 'download_add_to_cart',						
				]
			]
		);
				
		
		$this->add_control(
			'download_addtocart_icon',
			[
				'label' => __( 'Icon', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
				'condition' => [                    
					'dynamic_field_value' => 'download_add_to_cart',					
				]
			]
		);
		
		$this->add_control(
			'download_addtocart_icon_align',
			[
				'label' => __( 'Icon Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'briefcase-elementor-widgets' ),
					'right' => __( 'After', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
					'dynamic_field_value' => 'download_add_to_cart',						
				],
			]
		);

		$this->add_control(
			'download_addtocart_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'dynamic_field_value' => 'download_add_to_cart',						
				],
				'selectors' => [
					'{{WRAPPER}} #edd-cart.edd-align-icon-right i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #edd-cart.edd-align-icon-left i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'price_button',
			[
				'label' 		=> __( 'Display Price on Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'true',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'true',
				
			]
		);
		
		$this->add_control(
            'style_button',
            [
                'label' => __( 'Button Style', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'blue' => [
						'title' => __( 'Button', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-check',
					],
					'text-button' => [
						'title' => __( 'Plain Text', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-check',
					]
				],
				'default' => 'blue',
				
            ]
        );
		
		$this->add_control(
            'type_button',
            [
                'label' => __( 'Button Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'0' => [
						'title' => __( 'Add to Cart', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-check',
					],
					'1' => [
						'title' => __( 'Buy Now', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-check',
					]
				],
				'default' => '0',
				
            ]
        );
					
						
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_image',
				]	
			]
		);
		
		$this->add_control(
			'image_size',
			[
				'label' 		=> __( 'Image Size', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'full',
				'options' 		=> $this->get_img_sizes(),
			]
		);
		
		$this->add_control(
			'download_image_link',
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
			'free_label',
			[
				'label' 		=> __( 'Show Free Label', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
				
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_title',
				]	
			]
		);
		
		
		$this->add_control(
			'download_title_link',
			[
				'label' 		=> __( 'Title Link', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_excerpt',
			[
				'label' => __( 'Excerpt', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_excerpt',
				]	
			]
		);
		
		$this->add_control(
			'excerpt_length',
			[
				'label' 		=> __( 'Excerpt Length', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '15',
				'label_block' 	=> true,
			]
		);		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_price',
			[
				'label' => __( 'Price', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_price',
				]	
			]
		);
		
		$this->add_control(
			'download_price_absolute',
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
				
		if(function_exists('edd_price')) { 
		$free = edd_get_download_price( get_the_ID());
		
		if('0' == $free){
		
		$this->add_control(
            'free_price',
            [
                'label' => __( 'Price Style (FREE)  ', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'1' => [
						'title' => __( '$0.00', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-check',
					],
					'2' => [
						'title' => __( 'FREE', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-check',
					]
				],
				'default' => '1',				
            ]
        );
		}		
		}
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_details',
			[
				'label' => __( 'Details', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_details',
				]	
			]
		);
		
		$this->add_control(
			'date_published',
			[
				'label' 		=> __( 'Display Date Published', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'sale_count',
			[
				'label' 		=> __( 'Display Sales', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		/**
			 * version
			 
		$this->add_control(
			'version',
			[
				'label' 		=> __( 'Display Version', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		); */
		
		$this->add_control(
			'categories',
			[
				'label' 		=> __( 'Display Categories', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'tags',
			[
				'label' 		=> __( 'Display Tags', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_author',
			[
				'label' => __( 'Author', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'dynamic_field_value' => 'download_author',
				]	
			]
		);
						
		$this->add_control(
			'author_avatar',
			[
				'label' 		=> __( 'Display Avatar', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'author_store_name',
			[
				'label' 		=> __( 'Display Store Name', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
						
		$this->add_control(
			'author_name',
			[
				'label' 		=> __( 'Display Name', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'author_signup_date',
			[
				'label' 		=> __( 'Display Signup Date', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'author_website',
			[
				'label' 		=> __( 'Display Website', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-h-align-left',
					],
					'above' => [
						'title' => __( 'Above', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-v-align-top',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'separator' => 'before',
				'prefix_class' => 'edd-author-box-layout-image-',
			]
		);
		
		$this->add_control(
			'author_titles',
			[
				'label' 		=> __( 'Display Author Titles	', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
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
                    'dynamic_field_value' => 'download_title',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',				
				'selector' => '{{WRAPPER}} .bew-download-title',
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
					'{{WRAPPER}} .bew-download-title, {{WRAPPER}} .bew-download-title a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'title_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-download-title' => 'background: {{VALUE}};',
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
					'{{WRAPPER}} .bew-download-title:hover, {{WRAPPER}} .bew-download-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'title_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-download-title:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'title_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'title_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-download-title:hover' => 'border-color: {{VALUE}};',
				],
				
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
				'selector' => '{{WRAPPER}} .bew-download-title',
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
					'{{WRAPPER}} .bew-download-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .bew-download-title',
			]
		);		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_excerpt_style',
			[
				'label' => __( 'Excerpt', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_excerpt',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',				
				'selector' => '{{WRAPPER}} .bew-grid-excerpt',
			]
		);
				
		$this->start_controls_tabs( 'tabs_excerpt_style' );

		$this->start_controls_tab(
			'tab_excerpt_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'excerpt_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'excerpt_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-excerpt' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_excerpt_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'excerpt_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-excerpt:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'excerpt_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-excerpt:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'excerpt_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'bordere_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-grid-excerpt:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bordere',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-grid-excerpt',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'excerpt_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-grid-excerpt' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
				
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'excerpt_shadow',
				'selector' => '{{WRAPPER}} .bew-grid-excerpt',
			]
		);		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __( 'Price', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_price',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',				
				'selector' => '{{WRAPPER}} .download-price',
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .download-price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'price_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .download-price' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'price_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .download-price',
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
					'{{WRAPPER}} .download-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'price_box_shadow',
				'selector' => '{{WRAPPER}} .download-price',
			]
		);
				
		$this->add_control(
			'heading_price_dimension',
			[
				'label' => __( 'Dimensions', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'download_price_absolute' => 'yes',
                ]
				
			]
		);		
		
		$this->add_responsive_control(
			'price_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .download-price' => 'width: {{VALUE}}px;',						
				],					
				'condition' => [
                    'download_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'price_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .download-price' => 'height: {{VALUE}}px; line-height: {{VALUE}}px;',
				],
				'condition' => [
                    'download_price_absolute' => 'yes',
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
                    'download_price_absolute' => 'yes',
                ]
				
			]
		);
		
		$this->add_responsive_control(
			'price_position_y',
			[
				'label' => __( 'Top/Bottom', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-price-grid .price' => 'bottom: {{VALUE}}px;',
				],
				'condition' => [
                    'download_price_absolute' => 'yes',
                ]
			]
		);
		
		$this->add_responsive_control(
			'price_position_x',
			[
				'label' => __( 'Left/Right', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .download-price' => 'left: {{VALUE}}px;',
				],
				'condition' => [
                    'download_price_absolute' => 'yes',
                ]
			]
		);
				
		$this->end_controls_section();
						
		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => __( 'Add to cart', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_add_to_cart',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_typo',
				'selector' 		=> '{{WRAPPER}} .edd-download-button .button, {{WRAPPER}} #bew-cart .added_to_cart',
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
					'{{WRAPPER}} .edd-download-button .button' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd-download-button .button' => 'background: {{VALUE}};',
				],
				'condition' => [
                    'style_button' => 'blue',
                ]
				
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
					'{{WRAPPER}} .edd-download-button .button:hover' => 'color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd-download-button .button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
                    'style_button' => 'blue',
                ]
			]
		);

			
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'borderb_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .edd-download-button .button:hover' => 'border-color: {{VALUE}};',
				],				
			]
		);
		
		$this->add_control(
			'hover_animation',
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
				'name' => 'borderb',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .edd-download-button .button',
				'separator' => 'before',
				'condition' => [
                    'style_button' => 'blue',
                ]
				
			]
		);
		
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .edd-download-button .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'style_button' => 'blue',
                ]
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .edd-download-button .button',
			]
		);
				
				
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .edd-download-button .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .edd-download-button .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_price_options_style',
			[
				'label' => __( 'Price Options', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_add_to_cart',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'price_options_typo',
				'selector' 		=> '{{WRAPPER}} .edd_price_options'
			]
		);
		
		$this->start_controls_tabs( 'tabs_price_options_style' );

		$this->start_controls_tab(
			'tab_price_options_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'price_options_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd_price_options li' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'price_options_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd_price_options li' => 'background: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_price_options_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'price_options_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd_price_options li:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'price_options_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd_price_options li:hover' => 'background-color: {{VALUE}};' ,
					
				],
				
			]
		);

			
		$this->add_control(
			'price_options_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'borderpo_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .edd_price_options li:hover' => 'border-color: {{VALUE}};' ,					
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();		
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'borderpo',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .edd_price_options li',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'price_options_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .edd_price_options li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
						
		$this->add_control(
			'checked_color',
			[
				'label' 		=> __( 'Checked Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd_price_options input[type=radio]:checked:before' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'checked_background_color',
			[
				'label' 		=> __( 'Background Checked Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd_price_options input[type=radio]:checked' => 'background: {{VALUE}};',
				],
				
			]
		);
		
				
		$this->add_responsive_control(
			'price_options_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .edd_price_options ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'price_options_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .edd_price_options ul li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'dynamic_field_value' => 'download_image',
                ]
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label' => __( 'Size (%)', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 100,
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
					'{{WRAPPER}} .bew-download-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => __( 'Opacity (%)', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-download-image img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => __( 'Image Border', 'briefcase-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .bew-download-image img',
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
					'{{WRAPPER}} .bew-download-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bew-download-image img',
			]
		);

		$this->end_controls_section();
	
		$this->start_controls_section(
			'section_style_details',
			[
				'label' 		=> __( 'Details', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_details',
                ]
				]
		);
		
		$this->add_control(
			'details_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-details,{{WRAPPER}} .bew-edd-details a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'details_typo',
				'selector' 		=> '{{WRAPPER}} .bew-edd-details .details-categories, {{WRAPPER}} .bew-edd-details .details-tags, 
									{{WRAPPER}} .bew-edd-details .details-datePublished, {{WRAPPER}} .bew-edd-details .details-sales',
										
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		
		$this->add_responsive_control(
			'details_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-edd-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_responsive_control(
			'details_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-edd-details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();
				
		$this->start_controls_section(
			'section_author_image_style',
			[
				'label' => __( 'Author Image', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_author',
                ]
				
			]
		);

		$this->add_control(
			'author_image_vertical_align',
			[
				'label' => __( 'Vertical Align', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-v-align-middle',
					],
				],
				'prefix_class' => 'edd-author-box-image-valign-',
				'condition' => [
					'layout!' => 'above',
				],
			]
		);

		$this->add_responsive_control(
			'author_image_size',
			[
				'label' => __( 'Image Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .Author-avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'author_image_gap',
			[
				'label' => __( 'Gap', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}}.edd-author-box-layout-image-left .Author-avatar, 
					 body:not(.rtl) {{WRAPPER}}:not(.edd-author-box-layout-image-above) .Author-avatar' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'body:not(.rtl) {{WRAPPER}}.edd-author-box-layout-image-right .Author-avatar, 
					 body.rtl {{WRAPPER}}:not(.edd-author-box-layout-image-above) .Author-avatar' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right:0;',

					'{{WRAPPER}}.edd-author-box-layout-image-above .Author-avatar' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'author_image_border',
			[
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .Author-avatar img' => 'border-style: solid',
				],
			]
		);

		$this->add_control(
			'author_image_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .Author-avatar img' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'author_image_border_width',
			[
				'label' => __( 'Border Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .Author-avatar img' => 'border-width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'image_border' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_image_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .Author-avatar img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .Author-avatar img',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_author_text_style',
			[
				'label' => __( 'Author Text', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
                    'dynamic_field_value' => 'download_author',
                ]
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'author_text_typography',
				'selector' => '{{WRAPPER}} .Edd-Author-information',				
			]
		);
				
		$this->add_control(
			'author_color_name',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .Edd-Author-information .Author-name' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'author_color_value',
			[
				'label' 		=> __( 'Color Titles', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .Edd-Author-information .Author-value' => 'color: {{VALUE}};',
				],
				'condition' => [
					'author_titles' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'author_text_gap',
			[
				'label' => __( 'Gap', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .Edd-Author-information' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
	
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'author_border',
				'label' => __( 'Author Border', 'briefcase-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .Edd-Author-information',
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'author_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .Edd-Author-information li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_responsive_control(
			'author_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .Edd-Author-information li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();	

	}

	public function get_dynamic_fields($flat = false){

	    $fields = array();
		
		// EDD.
	    $fields[] = array(
            'code' => 'download_title',
            'name' => 'Download Title',
        );
		$fields[] = array(
            'code' => 'download_excerpt',
            'name' => 'Download Excerpt',
        );
	    $fields[] = array(
            'code' => 'download_image',
            'name' => 'Download Image',
        );
		$fields[] = array(
			'code' => 'download_price',
			'name' => 'Download Price',
		);
		$fields[] = array(
			'code' => 'download_add_to_cart',
			'name' => 'Download Add to cart',
		);
		$fields[] = array(
			'code' => 'download_details',
			'name' => 'Download Details',
		);
		$fields[] = array(
			'code' => 'download_author',
			'name' => 'Download Author',
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
		 * Get Download Data for EDD Grid Loop template
		 *
		 * @since 1.1.3
		 */
		public static function download_data_loop() {
			
			$download_data_loop = array();
				
			// Show firts download for loop template				
			if($GLOBALS['post']->post_type == 'elementor_library'){
				// Todo:: Get download from template meta field if available
					$args = array(
						'post_type' => 'download',
						'post_status' => 'publish',
						'posts_per_page' => 1
					);
					$preview_data = get_posts( $args );
					$download_data_loop = $preview_data[0];
									
			}
			
			return $download_data_loop;
					
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
	
	/**
	 * Render our custom field onto the page.
	 */
	protected function render() {
		$settings = $this->get_settings();
		
		// Vars
		$dynamic_field_value 			= $settings['dynamic_field_value'];			
		$download_title_link 	 		= $settings['download_title_link'];
		$download_image_link 	 		= $settings['download_image_link'];
		$download_price_absolute 	 	= $settings['download_price_absolute'];				
		$download_add_to_cart_options	= $settings['download_add_to_cart_options'];	
		
		$download_button_type			= $settings['download_button_type'];
		$download_type 					= $settings['download_type'];
		$download_id_custom 			= $settings['download_id'];	
		$download_addtocart_icon 		= $settings['download_addtocart_icon'];
		$download_addtocart_text   		= $settings['download_addtocart_text'];
		$price_button   				= $settings['price_button'];
		$style_button   				= $settings['style_button'];
		$type_button    				= $settings['type_button'];
		
		$free_label 					= $settings['free_label'];
		$free_price 					= $settings['free_price'];	
		
		$date_published 				= $settings['date_published'];
		$sale_count						= $settings['sale_count'];
		$version						= $settings['version'];
		$categories 					= $settings['categories'];
		$tags 							= $settings['tags'];
		
		$author_titles					= $settings['author_titles'];
		$avatar 						= $settings['author_avatar'];		
		$store_name 					= $settings['author_store_name'];
		$name 							= $settings['author_name'];
		$signup_date 					= $settings['author_signup_date'];
		$signup_website 				= $settings['author_website'];
		
			// Add to  cart Buttom
			if($dynamic_field_value == 'download_add_to_cart'  ){
				
				$download_data = $this->download_data_loop();
				
				if ( empty($download_data) ) {
				// Get global post
				global $post;
				} else {
				$post = $download_data;	
				}
				
				// Download Data
				$id	= $post->ID;

				// Show current or custom ID
				if('yes' == $download_type ){
				$download_id = $download_id_custom;				
				}
				else {			
				$download_id = $id;
				}		
						
				// Wrapper classes
				$wrap_classes = array( 'bew-edd-button','clr' );

						
				$wrap_classes = implode( ' ', $wrap_classes ); 
			
				// Inner classes button			
				$inner_classes_button = array( 'edd-submit');
			
				if ( $settings['hover_animation'] ) {
				$inner_classes_button[] = 'elementor-animation-' . $settings['hover_animation'] ;
				}			
				$inner_classes_button = implode( ' ', $inner_classes_button ); 			
				?>
				
				<div class="<?php echo esc_attr( $wrap_classes ); ?>">
				<?php
				if ( 'normal' == $download_button_type ) { ?>
					
									<?php
									// Display button
									
									if(function_exists('edd_price')) { ?>
										<div class="edd-download-button">									
										
										<?php echo edd_get_purchase_link(
										array( 
											'download_id' 	=> $download_id,
											'text' 			=> $download_addtocart_text,
											'price' 		=> $price_button,											
											'style' 		=> 'button',
											'color' 		=> $style_button,
											'direct' 		=> $type_button,
											'class'			=> $inner_classes_button,
											)
											); ?>
										
										</div><!--end .edd-download-buttons-->
									<?php } ?>

				</div><!-- .bew-edd-button -->
				<?php					
				} 
				else {
								  
					// Display button									
					if(function_exists('edd_price')) { 
					
					global $wp;
					$current_url = home_url(add_query_arg(array(),$wp->request));
						?>
						<div class="edd-download-button">
						   <?php
						 echo  sprintf( '<a rel="nofollow" href="%s" data-download_id="%s" class="%s"><i class="%s" aria-hidden="true"></i>%s</a>',
							   esc_url( $current_url .'/checkout?edd_action=add_to_cart&download_id='.$download_id ),
							   esc_attr( $download_id ),					   
							   esc_attr( isset( $class ) ? $class : 'button download-button-link' ),
							   esc_attr( $download_addtocart_icon ),
							   esc_html( $download_addtocart_text )	
							);
						?>
						</div><!--end .edd-download-buttons-->					
					</div><!-- .bew-edd-button -->
					<?php }				
			   
				}
			}
			
			elseif($dynamic_field_value == 'download_title'  ){
				
				$download_data = $this->download_data_loop();		
				
				// Download Data
				$id           = $download_data->ID;
				$permalink    = get_the_permalink( $id );
				$title        = $download_data->post_title;
				
				// Display title if there is a post title						
				
				echo '<div class="bew-download-title">'; 
				if('yes' == $download_title_link){					
				echo '<a href="' .$permalink .'" title="' .$title .'">';
				}
				if ($download_data){
				echo $title; 	
				}else {	
				$this->dynamic_field_checker();
				}
				if('yes' == $download_title_link){
				echo '</a>';				
				}
				echo '</div>';
			}	
			
			elseif($dynamic_field_value == 'download_excerpt'  ){
				
				$length = $settings['excerpt_length'];
				
				$download_data = $this->download_data_loop();
				
				if ( empty($download_data) ) {
				// Get global post
				global $post;
				} else {
				$post = $download_data;	
				}

				// Get Download data
				$id			= $post->ID;
				$excerpt	= $post->post_excerpt;
				$content 	= $post->post_content;

				// Display custom excerpt
				if ( $excerpt ) {
					$output = $excerpt;
				}

				// Check for more tag
				elseif ( strpos( $content, '<!--more-->' ) ) {
					$output = get_the_content( $excerpt );
				}

				// Generate auto excerpt
				else {
					$output = wp_trim_words( strip_shortcodes( get_the_content( $id ) ), $length );
				}

				// Display excerpt
				echo '<div class="bew-grid-excerpt clr">';				
				echo wp_kses_post( $output );
				echo '</div>';																
					
			}
			elseif($dynamic_field_value == 'download_image'  ){
				
				$download_data = $this->download_data_loop();
				
				if ( empty($download_data) ) {
				// Get global post
				global $post;
				} else {
				$post = $download_data;	
				}
				
				// Get Download data
				$id	= $post->ID;
				
				// Image size
				$img_size 		= $settings['image_size'];
				$img_size 		= $img_size ? $img_size : 'medium';
				
				// FREE Label				
				$price_free = edd_get_download_price( $id );									
				if ( 0 == $price_free ) {
				$label = 'free' ;
				}
				
				// Wrapper image classes
				$wrap_classes_image = array( 'bew-download-image' , 'image-wrap' );								
												
				$wrap_classes_image = implode( ' ', $wrap_classes_image );
								
				echo '<div class="' . esc_attr( $wrap_classes_image ). '" id="bew-image-' . $id .'">';
										
				// Display featured image if defined
				if ( has_post_thumbnail($id ) ) { ?>
					<div class="edd-grid-media clr">
						<?php
						if('yes' == $download_image_link){ ?>
							<a href="<?php the_permalink(); ?>" class="eddd-LoopDownload-link">	
						<?php } ?>
							
							<?php
							// Display post thumbnail
							echo get_the_post_thumbnail( $id, $img_size, array(
								'alt'		=> get_the_title(),
								'itemprop' 	=> 'image',
							) ); ?>
							
						<?php	
						if('yes' == $download_image_link){ ?>	
						</a>
						<?php }
						// Display Free label if $free_label yes
						if ( 'yes' == $free_label and 'free' == $label   ) { ?>
													
						<div class="product-label">
							<span class="free-label"><?php echo $label ?></span>
						</div>
					<?php }	?>	
					</div><!-- .edd-grid-media -->
				<?php							
				echo '</div>';
				 }
					
				
			}
			elseif($dynamic_field_value == 'download_price'  ){
				
				$download_data = $this->download_data_loop();
				
				if ( empty($download_data) ) {
				// Get global post
				global $post;
				} else {
				$post = $download_data;	
				}
				
				// Get Download data
				$id	= $post->ID;
				
				// FREE Label				
				$price_free = edd_get_download_price( $id );									
				if ( 0 == $price_free ) {
				$label = 'free' ;
				}
				
				// Wrapper price classes
				$wrap_classes_price = array( 'bew-price-grid download-price' );				
				
				if('yes' == $product_price_absolute){
					$wrap_classes_price[] ='price-absolute';
				}
				if('yes' == $product_price_sale){
					$wrap_classes_price[] ='price-sale';
				}				
				$wrap_classes_price = implode( ' ', $wrap_classes_price );
				
				echo '<div class="'. esc_attr( $wrap_classes_price ) . '">';
				
				
				// Display price									
					if(function_exists('edd_price')) { ?>
						
						<?php 
						if(edd_has_variable_prices($id)) {
						// if the download has variable prices, show the first one as a starting price
							echo edd_price_range($id);							
						} else {
											
							if($label == 'free' and '2' == $free_price) { ?>											
								<span class="edd_price" id="edd_price_free">FREE</span>
							<?php 	
							} else {
								edd_price($id);
							}
						}?>
						
					<?php }
								
				echo '</div>';
				
			}
			elseif($dynamic_field_value == 'download_details'  ){
				
				$download_data = $this->download_data_loop();
				
				if ( empty($download_data) ) {
				// Get global post
				global $post;
				} else {
				$post = $download_data;	
				}
				
				// Get Download data
				$id	= $post->ID;
				
				// Download Details
				
					// Wrapper classes
					$wrap_classes = array( 'bew-edd-details','clr' );						
					$wrap_classes = implode( ' ', $wrap_classes ); ?>

					<div class="<?php echo esc_attr( $wrap_classes ); ?>">
			
					<?php					
					//Published
					
					if ( 'yes' == $date_published ) :
					?>
						<span class="details-datePublished">
							<i class="fa fa-calendar"></i>
							<span class="details-value"><?php echo bew_edd_download_date_published(); ?></span>
						</span>
					<?php endif; ?>

					<?php
					
					//Sale count
					
					if ( 'yes' == $sale_count ) :
						$sales = edd_get_download_sales_stats( $id);
					?>
						<span class="details-sales">
							<i class="fa fa-shopping-cart"></i>
							<span class="details-value"><?php echo $sales; ?></span>
							<span>Sales</span>
						</span>
					<?php endif; ?>

					<?php
					/**
					 * Version.
					 
					if ( 'yes' == $version ) :

						$version = themedd_edd_download_version( get_the_ID());

						if ( $version ) : ?>
						<span class="downloadDetails-version">
							<span class="downloadDetails-name"><?php _e( 'Version:', 'themedd' ); ?></span>
							<span class="downloadDetails-value"><?php echo $version; ?></span>
						</span>
						<?php endif; ?>
					<?php endif; ?> 

					<?php

					
					/**
					 * Download categories.
					 */
								
					if ( 'yes' == $categories ) :

						$categories = bew_edd_download_categories( $id );

						if ( $categories ) : ?>
							<span class="details-categories">
								<i class="fa fa-folder"></i>						
								<span class="details-value"><?php echo $categories; ?></span>
							</span>
						<?php endif; ?>

					<?php endif; ?>

					<?php
					
					 //Tags.
					 
					if ( 'yes' == $tags ) :

						$tags = bew_edd_download_tags( $id );

						if ( $tags ) : ?>
						<span  class="details-tags">
							<i class="fa fa-tags"></i>						
							<span class="details-value"><?php echo $tags; ?></span>
						</span >
						<?php endif; ?>

					<?php endif; ?>	
											
					</div><!-- .bew-edd-price -->
					<?php 
			}
			elseif($dynamic_field_value == 'download_author'  ){
				
				$download_data = $this->download_data_loop();
				
				if ( empty($download_data) ) {
				// Get global post
				global $post;
				} else {
				$post = $download_data;	
				}
				
				// Get Download data
				$id	= $post->ID;
				
				// Get the author.
				$author = new WP_User( $post->post_author );
				
				if(function_exists('bew_is_edd_fes_active')) {
				if ( bew_is_edd_fes_active() ) {
					$vendor_url = (new Themedd_EDD_Frontend_Submissions)->author_url( get_the_author_meta( 'ID', $author->post_author ) );
				}
				}
										
				// Download Author
					?>
					<div class="Edd-Author">
					<?php
					//Author avatar.
				
					if ( 'yes' == $avatar ) {
						
						if ( bew_is_edd_fes_active() ) : ?>
							<div class="Author-avatar">
								<a class="vendor-url" href="<?php echo esc_url( $vendor_url ); ?>"><?php echo get_avatar( $author->ID, $options['avatar_size'] ); ?></a>
							</div>
						<?php else : ?>
							<div class="Author-avatar">
								<?php echo get_avatar( $author->ID, $options['avatar_size'] ); ?>
							</div>
						<?php endif;

					}
		
					//Author's store name.
					 
					if ( 'yes' == $store_name ) : ?>

						<?php if ( bew_is_edd_fes_active() ) :

							// Get the name of the store.
							$vendor_store = get_the_author_meta( 'name_of_store', $post->post_author );

							?>
						<h2 class="widget-title"><?php echo $vendor_store; ?></h2>
						<?php endif; ?>

					<?php endif; ?>
					
					<div class="Edd-Author-information">
						<ul>

						<?php
			
						//Author name.
						
						if ( 'yes' == $name ) : ?>
							<li class="Author-author">
								<?php if ('yes' == $author_titles) { ?>
								<span class="Author-name"><?php _e( 'Author:', 'briefcase-elementor-widgets' ); ?></span> 
								<?php } ?>
								<span class="Author-value">
									<?php if ( bew_is_edd_fes_active() ) : ?>
										<a class="vendor-url" href="<?php echo esc_url( $vendor_url ); ?>">
											<?php echo $author->display_name; ?>
										</a>
									<?php else : ?>
										<?php echo $author->display_name; ?>
									<?php endif; ?>
								</span>
							</li>
						<?php endif; ?>

						<?php
			
						//Author signup date.
						
						if ( 'yes' == $signup_date) : ?>
							<li class="Author-authorSignupDate">
								<?php if ('yes' == $author_titles) { ?>
								<span class="Author-name"><?php _e( 'Author since:', 'briefcase-elementor-widgets' ); ?></span>
								<?php } ?>
								<span class="Author-value"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $author->user_registered ) ); ?></span>
							</li>
						<?php endif; ?>

						<?php
			
						//Author website.
						 
						if ( 'yes' == $website ) :

							// Get the website.
							$website = get_the_author_meta( 'user_url', $post->post_author );

						?>

							<?php if ( ! empty( $website ) ) : ?>
							<li class="downloadAuthor-website">
								<?php if ('yes' == $author_titles) { ?>
								<span class="downloadAuthor-name"><?php _e( 'Website:', 'briefcase-elementor-widgets' ); ?></span>
								<?php } ?>
								<span class="downloadAuthor-value"><a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener"><?php echo esc_url( $website ); ?></a></span>
							</li>
							<?php endif; ?>

						<?php endif; ?>
						
						</ul>
					</div>
					
					</div>
					<?php
				
			}
						
			else {
			
			$this->dynamic_field_checker();			
			
			}
	}

	/**
	 * This is outputted while rending the page.
	 */
	protected function content_template() {
				
	}

}


Plugin::instance()->widgets_manager->register_widget_type( new EDD_Widget_Dynamic_Field() );