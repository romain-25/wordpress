<?php
/**
 * Woo Menu Cart Module
 */

namespace Elementor;



// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class BEW_Widget_Woo_Menu_Cart extends Widget_Base {
	
	public function get_name() {
		return 'bew-woo-menu-cart';
	}

	public function get_title() {
		return __( 'Woo Menu Cart', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];
	}
	
	public function get_script_depends() {
		return [ 'woocart-script', 'woo-menu-canvas' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
	

		$this->start_controls_section(
			'section_menu_cart',
			[
				'label' 		=> __( 'Woo Menu Cart', 'briefcase-elementor-widgets' ),
			]
		);

		
		$this->add_responsive_control(
			'position',
			[
				'label' 		=> __( 'Position', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart' => 'text-align: {{VALUE}};',
				],
			]
		);
		
				
		$this->add_control(
            'icon_type',
            [
                'label' => __( 'Icon Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'cart' => [
						'title' => __( 'Shopping Cart', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-cart',
					],
					'bag' => [
						'title' => __( 'Shopping Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-bag',
					],
					'basket' => [
						'title' => __( 'Shopping Basket', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-basket',
					],
					'line-handbag' => [
						'title' => __( 'Shopping Line Hand Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-handbag',
					],
					'line-bag' => [
						'title' => __( 'Shopping Line Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-bag',
					],
					'line-basket' => [
						'title' => __( 'Shopping Line Basket', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-basket',
					],
				],
				'default' => 'cart',
				
            ]
        );
		
		$this->add_control(
			'cart_style',
			[
				'label' 		=> __( 'Cart Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'b',
				'options' 		=> [
					'a' 			=> __( 'Circle', 'briefcase-elementor-widgets' ),
					'b' 			=> __( 'Square', 'briefcase-elementor-widgets' ),
					'c' 			=> __( 'Minimalist', 'briefcase-elementor-widgets' ),
					'd' 			=> __( 'Custom', 'briefcase-elementor-widgets' ),
				],
			]
		);
		
		$this->add_control(
			'mini_cart_style',
			[
				'label' 		=> __( 'Mini Cart Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'cart-dropdown',
				'options' 		=> [					
					'cart-dropdown' 			=> __( 'Dropdown', 'briefcase-elementor-widgets' ),
					'cart-canvas' 			=> __( 'Off Canvas', 'briefcase-elementor-widgets' ),
					
				],
			]
		);
				
		$this->add_control(
            'buttons_layout',
            [
                'label' => __( 'Buttons Layout', 'briefcase-elementor-widgets' ),
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
				'prefix_class' => 'bew-menu-cart-buttons-',
                
            ]
        );
		
		$this->add_control(
			'heading_cart_canvas',
			[
				'label' => __( 'Off Canvas', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'your_cart_text',
			[
				'label' => __( 'Heading Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Your Cart', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Your Cart', 'briefcase-elementor-widgets' ),
				'condition' => [
					'mini_cart_style' => 'cart-canvas',
				]
			]
		);
		
		$this->add_control(
			'close_text',
			[
				'label' => __( 'Close Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Close', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Close', 'briefcase-elementor-widgets' ),
				'condition' => [
					'mini_cart_style' => 'cart-canvas',
				]
			]
		);
		
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General Style', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'menucart_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'menucart_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => __( 'Cart Icon', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'size',
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
					'size' => 22,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .woo-header-cart i.fa, {{WRAPPER}} .woo-header-cart i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
			
		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'default' 		=> '#7a7a7a',
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart i' => 'color: {{VALUE}};',
				],
			]
		);
		
				
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon__hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart i:hover' => 'color: {{VALUE}};',
				],
			]
		);

				
		$this->end_controls_tab();

		$this->end_controls_tabs();		
		
		
		
		$this->add_control(
			'icon_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart .woo-menucart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'icon_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .woo-header-cart .woo-menucart',
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
					'{{WRAPPER}} .woo-header-cart .woo-menucart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_quantity',
			[
				'label' 		=> __( 'Cart Quantity', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'quantity_position_top',
			[
				'label' => __( 'Top', 'elementor' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .woo-header-cart .woo-cart-quantity' => 'top: {{VALUE}}px',
				],
			]
		);
		
		$this->add_control(
			'quantity_position_right',
			[
				'label' => __( 'Right', 'elementor' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .woo-header-cart .woo-cart-quantity' => 'right: {{VALUE}}px',
				],
			]
		);
		
		$this->add_control(
			'quantity_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart .woo-cart-quantity' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'quantity_bg_color',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart .woo-cart-quantity' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woo-header-cart span:before' => 'border-right-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'arrow_before_span',
			[
				'label' 		=> __( 'Display Arrow', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'cart_style' => [ 'b', 'd'],					
					],
			]
		);
		
		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Size (%)', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 75,
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
						'min' => 60,
						'max' => 100,
					],
				],
				'condition' => [
					'arrow_before_span' => [ 'yes'],
					'cart_style' => [ 'b', 'd'],					
					],
				'selectors' => [
					'{{WRAPPER}} .woo-arrow span:before' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
				
		$this->add_control(
			'text_after_span',
			[
				'label' 		=> __( 'Display Text', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'cart_style' => [ 'c', 'd'],					
					],
			]
		);
		
		$this->add_control(
			'text_after_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woo-item span:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'text_after_span' => [ 'yes'],
					'cart_style' => [ 'c', 'd'],	
					],				
			]
		);
		
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'quantity_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .woo-header-cart .woo-cart-quantity',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'quantity_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-header-cart .woo-cart-quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'quantity_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart .woo-cart-quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'quantity_typo',
				'selector' 		=> '{{WRAPPER}} .woo-header-cart .woo-cart-quantity',
				'scheme' 		=> Scheme_Typography::TYPOGRAPHY_1,
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_mini_cart',
			[
				'label' 		=> __( 'Mini Cart', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
				
		$this->add_responsive_control(
			'mini_cart_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 800,
					],
				],
				'default' => [
					'size' => 500,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .bew-menu-cart .cart-dropdown .shopping-cart-content, {{WRAPPER}} .bew-menu-cart .cart-canvas' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'mini_cart_width_color_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart .shopping-cart' => 'background-color: {{VALUE}};',
				],
			]
		);
				
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'mini_cart_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-menu-cart .cart-dropdown .shopping-cart-content, {{WRAPPER}} .bew-menu-cart .cart-canvas',
			]
		);
			
		$this->add_control(
			'heading_products_list',
			[
				'label' => __( 'Product List', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'products_list_typography',				
				'selector' => '{{WRAPPER}} .bew-mini-cart .shopping-cart .owp-grid-wrap .owp-grid.content h3, {{WRAPPER}} .bew-mini-cart .shopping-cart .owp-grid-wrap .owp-grid.content',
			]
		);
		
		$this->add_responsive_control(
			'image_size',
			[
				'label' => __( 'Image Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .bew-menu-cart .shopping-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid.thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'img_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-menu-cart .shopping-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid.thumbnail img',
				
			]
		);
		
			$this->add_control(
			'border_radius_img',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-menu-cart .shopping-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid.thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'amount_products_color',
			[
				'label' 		=> __( 'Amount Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .woocommerce-mini-cart .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'mini_cart_products_remove',
			[
				'label' 		=> __( 'Remove Button Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid a.remove' => 'color: {{VALUE}}; border-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_control(
			'mini_cart_products_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart .shopping-cart-content' => 'background-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_responsive_control(
			'mini_cart_products_padding',
			[
				'label' 		=> __( 'Padding Product', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart .shopping-cart .shopping-cart-content li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'mini_cart_products_margin',
			[
				'label' 		=> __( 'Margin Products', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart .shopping-cart .shopping-cart-content ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_products_total',
			[
				'label' => __( 'Total', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'products_total_typography',				
				'selector' => '{{WRAPPER}} .bew-mini-cart .shopping-cart .shopping-cart-content .total strong, {{WRAPPER}} .bew-mini-cart .shopping-cart .shopping-cart-content .total .amount',
			]
		);
		
		$this->add_control(
			'amount_total_color',
			[
				'label' 		=> __( 'Amount Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .woocommerce-mini-cart__total .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'mini_cart_total_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .woocommerce-mini-cart__total' => 'background-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_responsive_control(
			'mini_cart_total_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart .shopping-cart-content .total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'mini_cart_total_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart .shopping-cart-content .total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_products_button',
			[
				'label' => __( 'Buttons', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'products_button_typography',				
				'selector' => '{{WRAPPER}} .bew-mini-cart .shopping-cart .shopping-cart-content .buttons .button',
			]
		);
				
		$this->add_control(
			'mini_cart_buttons_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .woocommerce-mini-cart__buttons' => 'background-color: {{VALUE}};',					
				],
			]
		);

		$this->add_control(
			'button_cart_show',
			[
				'label' 		=> __( 'Display View Cart Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
			]
		);
		
		$this->add_control(
			'heading_button_cart',
			[
				'label' => __( 'View Cart', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
				
		$this->start_controls_tabs( 'tabs_button_cart_style' );

		$this->start_controls_tab(
			'tab_button_cart_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'button_cart_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .button:first-child' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'button_background_cart_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .button:first-child' => 'background: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_cart_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'button_color_cart_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .button:first-child:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'button_background_color_cart_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .button:first-child:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);

			
		$this->add_control(
			'button_border_cart_hover',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'cart_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .shopping-cart-content .buttons .button:first-child:hover' => 'border-color: {{VALUE}};',
				],				
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cart_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .shopping-cart-content .buttons .button:first-child',
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'heading_button_checkout',
			[
				'label' => __( 'Checkout', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,							
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_checkout_style' );

		$this->start_controls_tab(
			'tab_button_checkout_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'button_checkout_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .checkout' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_checkout_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .checkout' => 'background: {{VALUE}};',
				],				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_checkout_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'button_color_checkout_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .checkout:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color_checkout_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-content .buttons .checkout:hover' => 'background-color: {{VALUE}};',
				],				
			]
		);

			
		$this->add_control(
			'button_border_checkout_hover',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'checkout_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .shopping-cart-content .buttons .checkout:hover' => 'border-color: {{VALUE}};',
				],				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .shopping-cart-content .buttons .checkout',								
			]
		);
		
		$this->add_responsive_control(
			'mini_cart_button_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart .shopping-cart-content .buttons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'mini_cart_button_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart .shopping-cart-content .buttons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cart_dropdown',
			[
				'label' 		=> __( 'Cart Dropdown', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'mini_cart_style' => 'cart-dropdown',					
				],
				
			]
		);
		
		$this->add_control(
			'cart_dropdown_position_top',
			[
				'label' => __( 'Position Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .bew-menu-cart .cart-dropdown .shopping-cart-content' => 'top: {{VALUE}}px',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cart_canvas',
			[
				'label' 		=> __( 'Off Canvas', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'mini_cart_style' => 'cart-canvas',					
				],
			]
		);
		
		$this->add_control(
			'heading_cart_canvas_style',
			[
				'label' => __( 'Header', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'title_canvas_color',
			[
				'label' 		=> __( 'Title Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .cart-canvas .shopping-cart-header .heading--add-small' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'close_canvas_color',
			[
				'label' 		=> __( 'Close Button Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .cart-canvas .shopping-cart-header .drawer__btn-close' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'off_canvas_color_bg',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .cart-canvas .shopping-cart-header' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_canvas_header_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_canvas_header_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shopping-cart-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cart_canvas_header_border',
				'label' => __( 'Header Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .shopping-cart-header',
			]
		);
		
		$this->add_control(
			'inline_content_style',
			[
				'label' 		=> __( 'Content Inline Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'content-inline-',
			]
		);
		
		$this->end_controls_section();
		
	}
	
	
	public function menu_canvas() {
	
		$settings = $this->get_settings(); 	
		$menu_canvas = 'yes';
		
		// If off canvas Menu Cart
		if ( 'yes' == $menu_canvas ){			
		
		
		}
	}
	
	/**
		* Creates the WooCommerce link for the widget
		*
		* @since 1.1.0
		*/
	public function bew_woomenucart($icon_type,$wrap_classes,$type_classes, $view_cart, $mini_cart_style, $your_cart, $close ) {
				
		if( class_exists( 'WooCommerce' ) ) { 	
		$woo = WC()->cart;
	
			if(is_null($woo)){ 
			$url 	= wc_get_cart_url();
			$count 	= WC()->cart->cart_contents_count;
			// Menu Cart WooCommerce
			if( class_exists( 'WooCommerce' ) ) { ?>
						
					<div class="bew-menu-cart">
						<div class="woo-header-cart <?php echo esc_attr( $wrap_classes  ); ?>" data-icon="<?php echo esc_attr( $icon_type ); ?>" data-type="<?php echo esc_attr( $type_classes ); ?>">
						<a class="woo-menucart <?php echo esc_attr( $type_classes ); ?>" href=" <?php echo $url?>" title="View your shopping cart">				
						<i class="<?php echo esc_attr( $icon_type ); ?>"></i> 
						<span class="woo-cart-quantity <?php echo esc_attr( $type_classes ); ?>"><?php echo $count ?></span>				
						</a>
						</div>
						<div class="bew-mini-cart bew-mini-<?php echo esc_attr( $mini_cart_style ); ?>">
							<div class="<?php echo esc_attr( $mini_cart_style ); ?> shopping-cart <?php echo esc_attr( $view_cart ); ?>">
								<?php 
								if($mini_cart_style == 'cart-canvas'){
								?>
								<div class="shopping-cart-header">								
								  <h3 class="heading--add-small"><?php echo $your_cart; ?></h3>								  
								  <button class="drawer__btn-close btn" type="button">
									<span><?php echo $close; ?></span>
								  </button>
								</div>
								<?php 	
								}
								?>
								<div class="shopping-cart-content">
								
								</div>
							</div>
							<?php 
								if($mini_cart_style == 'cart-canvas'){
								?>
							<div class="bew-menu-canvas-overlay"></div>
							<?php 	
								}
								?>
						</div>
					</div>
							
						<?php }
				
			} else {
			
			if($mini_cart_style == 'cart-dropdown'){ 
			$url = wc_get_cart_url();
			} else {
			$url = "";	
			}
			$count = WC()->cart->cart_contents_count;
			// Menu Cart WooCommerce
					if( class_exists( 'WooCommerce' ) ) { ?>
					
					<div class="bew-menu-cart">
						<div class="woo-header-cart <?php echo esc_attr( $wrap_classes  ); ?>" data-icon="<?php echo esc_attr( $icon_type ); ?>" data-type="<?php echo esc_attr( $type_classes ); ?>">
							<a class="woo-menucart <?php echo esc_attr( $type_classes ); ?>" href=" <?php echo $url?>" title="View your shopping cart">				
							<i class="<?php echo esc_attr( $icon_type ); ?>"></i> 
							<span class="woo-cart-quantity <?php echo esc_attr( $type_classes ); ?>"><?php echo $count ?></span>				
							</a>
						</div>
						<div class="bew-mini-cart bew-mini-<?php echo esc_attr( $mini_cart_style ); ?>">
							<div class="<?php echo esc_attr( $mini_cart_style ); ?> shopping-cart <?php echo esc_attr( $view_cart ); ?>">
								<?php 
								if($mini_cart_style == 'cart-canvas'){
								?>
								<div class="shopping-cart-header">								
								  <h3 class="heading--add-small"><?php echo $your_cart; ?></h3>								  
								  <button class="drawer__btn-close btn" type="button">
									<span><?php echo $close; ?></span>
								  </button>
								</div>
								<?php 	
								}
								?>
								<div class="shopping-cart-content">
								<?php woocommerce_mini_cart() ?>
								</div>
							</div>
							<?php 
								if($mini_cart_style == 'cart-canvas'){
								?>
							<div class="bew-menu-canvas-overlay"></div>
							<?php 	
								}
								?>
						</div>
					</div>
							
					<?php }
			}	
		}
		
		}
				
	protected function render() {
		$settings = $this->get_settings(); 	
		
		$cart = $settings['cart_style'];	
		$icon = $settings['icon_type'];			
		$arrow = $settings['arrow_before_span'];
		$item = $settings['text_after_span'];
		$mini_cart_style = $settings['mini_cart_style'];
		$your_cart = $settings['your_cart_text'];
		$close = $settings['close_text'];
		
		global $icon_type;
		
		// Icon type
					if ( 'cart' == $icon) {
					$icon_type = 'fa fa-shopping-cart';
					}
					if ( 'bag' == $icon) {
					$icon_type = 'fa fa-shopping-bag';
					}
					if ( 'basket' == $icon) {
					$icon_type = 'fa fa-shopping-basket';
					}
					if ( 'line-handbag' == $icon) {
					$icon_type = 'icon-handbag';
					}
					if ( 'line-bag' == $icon) {
					$icon_type = 'icon-bag';
					}
					if ( 'line-basket' == $icon) {
					$icon_type = 'icon-basket';
					}
					
		// If quantity after span
					$wrap_classes = array( );
					if ( 'yes' == $arrow and 'b' == $cart) {
					$wrap_classes[] = 'woo-arrow';
					}
					if ( 'yes' == $arrow and 'd' == $cart) {
					$wrap_classes[] = 'woo-arrow';
					}
										
		// If quantity before span
					
					if ( 'yes' == $item and 'c' == $cart) {
					$wrap_classes[] = 'woo-item';
					}					
					if ( 'yes' == $item and 'd' == $cart) {
					$wrap_classes[] = 'woo-item';
					}			
									
			$wrap_classes = implode( ' ', $wrap_classes );		
										
		// cart style options
					
					if ( 'a' == $cart ) {
						$type_classes = 'circle';
					}
					elseif ( 'b' == $cart ) {
						$type_classes = 'square';
					}
					elseif ( 'c' == $cart ) {
						$type_classes = 'minimalist';
					}
					elseif ( 'd' == $cart ) {
						$type_classes = 'custom';
					}
		// cart dropdown style Display View Cart Button
					
					if ( '' == $settings['button_cart_show'] ) {
						$view_cart = 'hide_view_cart';
					}else{
						$view_cart = '';
					}
			
		$passedValues = array( 'icon_type' => $icon_type, 'type_classes' => $type_classes );

		wp_localize_script( 'woocart-script', 'passed_object', $passedValues );
			
		$this->bew_woomenucart($icon_type,$wrap_classes,$type_classes, $view_cart, $mini_cart_style, $your_cart, $close );
	
	
		
	}
	
}

Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_Woo_Menu_Cart() );