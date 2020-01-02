<?php
/**
 * Edd Grid Module
 */

namespace Elementor;

use Elementor;
use Elementor\Plugin;
use Elementor\Post_CSS_File;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class bew_Widget_Edd_Grid extends Widget_Base {

	public function get_name() {
		return 'bew-edd-grid';
	}

	public function get_title() {
		return __( 'Edd Grid', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];
	}

	public function get_script_depends() {
		return [ 'bew-edd-grid', 'isotope', 'imagesloaded', 'woo-grid', 'woo-product-filter' ];
	}
	
	public static function get_templates() {
		return Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
	}
	
	public static function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
				<div class="elementor-widget-template-empty-templates-title">' . __( 'You Haven’t Saved Templates Yet.', 'elementor-pro' ) . '</div>
				<div class="elementor-widget-template-empty-templates-footer">' . __( 'Want to learn more about Elementor library?', 'elementor-pro' ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . __( 'Click Here', 'elementor-pro' ) . '</a>
				</div>
				</div>';
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Template', 'briefcase-elementor-widgets' ),
			]
		);
		
				
		$this->add_control(
			'custom_template',
			[
				'label' 		=> __( 'Use Custom Template', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);

		$templates = $this->get_templates();

		if ( empty( $templates ) ) {

			$this->add_control(
				'no_templates',
				[
					'label' => false,
					'type' => Controls_Manager::RAW_HTML,
					'raw' => $this->empty_templates_message(),
					'condition' => [
                    'custom_template' => 'yes',
				]
				]
			);
			
		}
		
		if ( !empty( $templates ) ) {
		$options = [
			'0' => '— ' . __( 'Select', 'briefcase-elementor-widgets' ) . ' —',
		];

		$types = [];

		foreach ( $templates as $template ) {
			$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			$types[ $template['template_id'] ] = $template['type'];
		}
		

		$this->add_control(
			'template_id',
			[
				'label' => __( 'Choose Template', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $options,
				'types' => $types,
				'label_block'  => true,
				'condition' => [
                    'custom_template' => 'yes',
				]
			]
		);
		
		$button = '<div class="elementor-button elementor-button-default elementor-edit-template-bew" id="bb"><i class="fa fa-pencil"></i> Edit Template</div>';
				
		$this->add_control(
			'field_preview',
			[
				'label'   => esc_html__( 'Code', 'briefcase-elementor-widgets' ),
				'type'    => Controls_Manager::RAW_HTML,
				'separator' => 'none',
				'show_label' => false,
				'raw' => $button,
				'condition' => [
                    'custom_template' => 'yes',
				]
			]
		);
		
		}

		$this->end_controls_section();

		$this->start_controls_section(
			'section_edd_grid',
			[
				'label' 		=> __( 'Edd Grid', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'count',
			[
				'label' 		=> __( 'Edds Per Page', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'You can enter "-1" to display all posts.', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '6',
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'columns',
			[
				'label' 		=> __( 'Grid Columns', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> '3',
				'options' 		=> [
					'1' 		=> '1',
					'2' 		=> '2',
					'3' 		=> '3',
					'4' 		=> '4',
					'5' 		=> '5',
					'6' 		=> '6',
				],
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
			]
		);

		$this->add_control(
			'include_categories',
			[
				'label' 		=> __( 'Include Categories', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'Enter the categories slugs seperated by a "comma"', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'exclude_categories',
			[
				'label' 		=> __( 'Exclude Categories', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'Enter the categories slugs seperated by a "comma"', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block' 	=> true,
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'section_elements',
            [
                'label' => __( 'Elements', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'custom_template' => '',
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
			'overlay',
			[
				'label' 		=> __( 'Display Image Overlay', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_control(
			'title',
			[
				'label' 		=> __( 'Display Title', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);

		$this->add_control(
			'price',
			[
				'label' 		=> __( 'Display Price', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
			]
		);
		
				
		$this->add_control(
            'free_price',
            [
                'label' => __( 'FREE Price Style ', 'briefcase-elementor-widgets' ),
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
				'condition' => [
                    'price' => 'yes',
                ]
            ]
        );
		
		$this->add_control(
			'purchase_button',
			[
				'label' 		=> __( 'Display Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
			]
		);
		
		$this->add_control(
			'text_button',
			[
				'label' 		=> __( 'Button Text', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Add to Cart', 'briefcase-elementor-widgets' ),
				'label_block' 	=> true,
				'condition' => [
                    'purchase_button' => 'yes',
                ]
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
				'condition' => [
                    'purchase_button' => 'yes',
                ]
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
				'condition' => [
                    'purchase_button' => 'yes',
                ]
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
				'condition' => [
                    'purchase_button' => 'yes',
                ]
            ]
        );
		
		

		$this->add_control(
			'cat',
			[
				'label' 		=> __( 'Display Categories', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
			]
		);

		$this->add_control(
			'excerpt',
			[
				'label' 		=> __( 'Display Excerpt', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
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
		
		$this->add_control(
			'free_label',
			[
				'label' 		=> __( 'Display FREE Label', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
			]
		);
		
		$this->add_control(
			'effect_element',
			[
				'label' 		=> __( 'Shadow Effect', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_grid',
			[
				'label' 		=> __( 'Grid', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'grid_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'grid_border_color',
			[
				'label' 		=> __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-inner' => 'border-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		
		$this->start_controls_section(
			'section_title',
			[
				'label' 		=> __( 'Title', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'title' => 'yes',
                ]
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-details .bew-grid-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label' 		=> __( 'Color: Hover', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-details .bew-grid-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'title_typo',
				'selector' 		=> '{{WRAPPER}} .bew-edd-grid .bew-grid-details .bew-grid-title',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-details .bew-grid-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_price',
			[
				'label' 		=> __( 'Price', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'price' => 'yes',
                ]
			]
		);
		
		$this->add_control(
			'price_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-details .product-price' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'price_typo',
				'selector' 		=> '{{WRAPPER}} .bew-grid-details .product-price',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_3,
			]
		);
		
		$this->add_responsive_control(
			'price_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-grid-details .product-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_button_o',
			[
				'label' 		=> __( 'Overlay Buttons', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'overlay' => 'yes',
                ]
			]
		);

		$this->add_control(
			'buttons_size_o',
			[
				'label' => __( 'Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 16,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .bew-grid-inner .mt-more-btn, {{WRAPPER}} .bew-grid-inner .mt-cart-btn' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				
			]
		);

		$this->add_control(
			'buttons_color_o',
			[
				'label' => __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-inner .mt-more-btn:hover, {{WRAPPER}} .bew-grid-inner .mt-cart-btn:hover' => 'background: {{VALUE}};',					
				],
			]
		);
		
		$this->add_control(
			'button_border_color_o',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-grid-inner .mt-more-btn:hover, {{WRAPPER}} .bew-grid-inner .mt-cart-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'button_padding_o',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-grid-inner .mt-more-btn, {{WRAPPER}} .bew-grid-inner .mt-cart-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_purchase',
			[
				'label' 		=> __( 'Purchase Buttons', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'purchase_button' => 'yes',
                ]
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
			'button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd-submit.button' => 'background: {{VALUE}};',
				],
				'condition' => [
                    'style_button' => 'blue',
                ]
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd-submit.button' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_typo',
				'selector' 		=> '{{WRAPPER}} .edd-submit.button',				
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .edd-submit.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .edd-submit.button',
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
					'{{WRAPPER}} .edd-submit.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd-submit.button:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
                    'style_button' => 'blue',
                ]
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .edd-submit.button:hover' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .edd-submit.button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
                    'style_button' => 'blue',
                ]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tab();

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_excerpt',
			[
				'label' 		=> __( 'Excerpt', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'excerpt' => 'yes',
                ]
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-details .bew-grid-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'excerpt_typo',
				'selector' 		=> '{{WRAPPER}} .bew-edd-grid .bew-grid-details .bew-grid-excerpt',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_meta',
			[
				'label' 		=> __( 'Meta', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'cat' => 'yes',
                ]
			]
		);

		$this->add_control(
			'meta_bg',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-meta' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-meta, {{WRAPPER}} .bew-edd-grid .bew-grid-meta li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'meta_color_hover',
			[
				'label' 		=> __( 'Color: Hover', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-edd-grid .bew-grid-meta li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'meta_typo',
				'selector' 		=> '{{WRAPPER}} .bew-edd-grid .bew-grid-meta',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);

        $this->end_controls_section();

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
	
	public function edd_template_system($countp) {
	 // Create a template system for EDD Grid Widget
	$settings = $this->get_settings();
	
	$template_id = $this->get_settings( 'template_id' );
		
	?>	
	<div id="Edd-template" class="edd-shop-template">
	<?php
	if ( '1' == $countp ) {					
	echo Elementor\Plugin::instance()->frontend->get_builder_content( $template_id,$with_css = true );
	} else {
	echo Elementor\Plugin::instance()->frontend->get_builder_content( $template_id);	
	}
	?>
	</div>
	<?php
	}
	
	public function edd_main_skin() {
	 // Create a main style grid for EDD Grid Widget
	 $settings = $this->get_settings();
	 
	 // Vars
			$grid_style 	= $settings['grid_style'];
			$equal_heights 	= $settings['grid_equal_heights'];
			$overlay 		= $settings['overlay'];
			$title   		= $settings['title'];
			$price   		= $settings['price'];
			$button   		= $settings['purchase_button'];
			$text_button   	= $settings['text_button'];
			$price_button   = $settings['price_button'];
			$style_button   = $settings['style_button'];
			$type_button    = $settings['type_button'];
			$excerpt 		= $settings['excerpt'];
			$columns 		= $settings['columns'];
			$cat 			= $settings['cat'];
			$effect 		= $settings['effect_element'];
			$free_label 	= $settings['free_label'];
			$free_price 	= $settings['free_price'];						

			// Image size
			$img_size 		= $settings['image_size'];
			$img_size 		= $img_size ? $img_size : 'medium';

	// FREE Label					
		$price_free = edd_get_download_price( get_the_ID() );									
		if ( 0 == $price_free ) {
			$label = 'free' ;
		}
	// Create new post object.
		$post = new \stdClass();

		// Get post data
			$get_post = get_post();

		// Post Data
			$post->ID           = $get_post->ID;
			$post->permalink    = get_the_permalink( $post->ID );
			$post->title        = $get_post->post_title;
	
	// Open details if the elements are yes
		if ( 'yes' == $title
			|| 'yes' == $excerpt) { 													
		 
		// Box Effect if the elements are yes
			if ( 'yes' == $effect ) { ?>
				<div class="bew-grid-inner bew-effect clr"> 
				<?php }							
			else { ?>
				<div class="bew-grid-inner clr">									
			<?php } ?>
								
			<?php
		// Display thumbnail if enabled and defined
			if ( has_post_thumbnail() ) { ?>

				<div class="bew-grid-media clr">												
				<figure>  
				<?php
				// Display post thumbnail
				the_post_thumbnail( $img_size, array(
				'alt'		=> get_the_title(),
				'itemprop' 	=> 'image',
				) ); ?>
				</figure>
				<?php
		// Display Free label if $free_label yes
				if ( 'yes' == $free_label and 'free' == $label   ) { ?>
												
					<div class="product-label">
						<span class="free-label"><?php echo $label ?></span>
					</div>
				<?php }				
				if ( 'yes' == $overlay ) { ?>
					<div class="product-details">			
					<div class="product-btns">
						<a href="<?php the_permalink(); ?>" class="mt-more-btn" title="<?php echo esc_attr( 'View', 'briefcasewp' ); ?>"><i class="fa fa-search"></i></a>
						<a href="<?php echo esc_url( site_url() ). '/checkout?edd_action=add_to_cart&download_id='.get_the_ID(); ?>" class="mt-cart-btn" title="<?php echo esc_attr( 'Add to Cart', 'briefcasewp' ); ?>"><i class="fa fa-shopping-bag"></i></a>
					</div><!-- .product-btns -->												
					</div><!-- .product-details -->
				<?php } ?>
				</div><!-- .bew-grid-media -->
				<?php }
				
		// Open details element if the title or excerpt are yes
				if ( 'yes' == $title
					|| 'yes' == $excerpt ) { ?>
				<div class="bew-grid-details<?php echo esc_attr( $details_class ); ?> clr">
					<?php
					
			// Display title if $title is yes and there is a post title
					if ( 'yes' == $title ) { ?>
						<h2 class="bew-grid-title entry-title">
						<a href="<?php echo $post->permalink; ?>" title="<?php the_title(); ?>"><?php echo $post->title; ?></a>
						</h2>
					<?php }
					
			// Display excerpt if $excerpt is yes
					if ( 'yes' == $excerpt ) { ?>
						<div class="bew-grid-excerpt clr">
						<?php oew_excerpt( $settings['excerpt_length'] ); ?>
						</div>												
					<?php } 

			// Display price									
					if(function_exists('edd_price') && 'yes' == $price ) { ?>
						<div class="product-price">
						<?php 
						if(edd_has_variable_prices(get_the_ID())) {
						// if the download has variable prices, show the first one as a starting price
							echo 'Starting at: '; edd_price(get_the_ID());
						} else {
											
							if($label == 'free' and '2' == $free_price) { ?>											
								<span class="edd_price" id="edd_price_free">FREE</span>
							<?php 	
							} else {
								edd_price(get_the_ID());
							}
						}?>
						</div><!--end .product-price-->
					<?php }
					
			// Display button									
						if(function_exists('edd_price') && 'yes' == $button ) { ?>
							<div class="product-buttons">
							<?php if(!edd_has_variable_prices(get_the_ID())) { ?>
										
									<?php echo edd_get_purchase_link(
										array( 
											'download_id' 	=> get_the_ID(),
											'text' 			=> $text_button,
											'price' 		=> $price_button,											
											'style' 		=> 'button',
											'color' 		=> $style_button,
											'direct' 		=> $type_button,											
											)
											); ?>
										
							<?php } ?>
							</div><!--end .product-buttons-->
					<?php } ?>
				</div><!-- .bew-grid-details -->
				<?php } ?>
			</div><!-- .bew-grid-inner -->
			
			<?php }	
	}

	protected function render() {
		$settings = $this->get_settings();

		$args = array(
	        'post_type'         => 'download',
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

	    // Include category
		if ( ! empty( $include ) ) {

			// Sanitize category and convert to array
			$include = str_replace( ', ', ',', $include );
			$include = explode( ',', $include );

			// Add to query arg
			$args['tax_query'][] = array(
				'taxonomy' => 'download_category',
				'field'    => 'slug',
				'terms'    => $include,
				'operator' => 'IN',
			);

		}

		// Exclude category
		if ( ! empty( $exclude ) ) {

			// Sanitize category and convert to array
			$exclude = str_replace( ', ', ',', $exclude );
			$exclude = explode( ',', $exclude );

			// Add to query arg
			$args['tax_query'][] = array(
				'taxonomy' => 'download_category',
				'field'    => 'slug',
				'terms'    => $exclude,
				'operator' => 'NOT IN',
			);

		}

	    // Build the WordPress query
	    $bew_query = new \WP_Query( $args );

		// Output posts
		if ( $bew_query->have_posts() ) :
		
			// Vars
			$grid_style 		= $settings['grid_style'];
			$equal_heights 		= $settings['grid_equal_heights'];			
			$columns 			= $settings['columns'];
			$custom_template 	= $settings['custom_template'];

			// Wrapper classes
			$wrap_classes = array( 'bew-edd-grid', 'briefcasewp-row', 'clr' );

			if ( 'masonry' == $grid_style ) {
				$wrap_classes[] = 'bew-masonry';
			}

			if ( 'yes' == $equal_heights ) {
				$wrap_classes[] = 'match-height-grid';
			}

			
			$wrap_classes = implode( ' ', $wrap_classes ); ?>

			<div class="<?php echo esc_attr( $wrap_classes ); ?>">

				<?php
				// Define counter var to clear floats
				$count = '';

				// Start loop
				while ( $bew_query->have_posts() ) : $bew_query->the_post();

					// Counter
					$count++;
					$countp++;

					// Inner classes
					$inner_classes 		= array( 'bew-grid-entry', 'col', 'clr' );
					$inner_classes[] 	= 'span_1_of_' . $columns;
					$inner_classes[] 	= 'col-' . $count;

					if ( 'masonry' == $grid_style ) {
						$inner_classes[] = 'isotope-entry';
					}

					$inner_classes = implode( ' ', $inner_classes );

					// If equal heights
					$details_class = '';
					if ( 'yes' == $equal_heights ) {
						$details_class = ' match-height-content';
					}

					// Meta class
					$meta_class = '';
					if ( 'false' == $comments
						|| 'false' == $cat ) {
						$meta_class = ' bew-center';
					}							
									

					// Only display items if there is content to show
					if ( has_post_thumbnail()
						|| 'yes' == $title
						|| 'yes' == $excerpt
					) { 					
						// Post loop type
						?>
						<div id="post-<?php the_ID(); ?>" <?php post_class( $inner_classes ); ?>>						
						<?php
						if ($custom_template == 'yes'){
						$this->edd_template_system($countp);	
						} else {
						$this->edd_main_skin();	
						}?>
						</div>

					<?php } ?>

					<?php
					// Reset entry counter
					if ( $count == $columns ) {
						$count = '0';
					} ?>

				<?php
				// End entry loop
				endwhile; ?>

			</div><!-- .bew-edd-grid-->

			<?php
			// Reset the post data to prevent conflicts with WP globals
			wp_reset_postdata();

		// If no posts are found display message
		else : ?>

			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'briefcase-elementor-widgets' ); ?></p>

		<?php
		// End post check
		endif; ?>

	<?php
	}
	
	public function render_plain_content() {}

}

Plugin::instance()->widgets_manager->register_widget_type( new bew_Widget_Edd_Grid() );