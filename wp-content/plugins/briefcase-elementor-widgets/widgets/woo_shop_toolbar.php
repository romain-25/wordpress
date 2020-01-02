<?php
/**
 * Woo Shop Toolbar Module
 */

namespace Elementor;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class BEW_Widget_Woo_Shop_Toolbar extends Widget_Base {


	public function get_name() {
		return 'bew-woo-shop-toolbar';
	}

	public function get_title() {
		return __( 'Woo Shop Toolbar', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];
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
		
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General Style', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'minicart_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'minicart_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .mini-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'size' => 90,
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
			'section_cart_dropdown',
			[
				'label' 		=> __( 'Cart Dropdown', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'cart_dropdown_position_top',
			[
				'label' => __( 'Top', 'briefcase-elementor-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .mini-cart .cart-dropdown' => 'top: {{VALUE}}px',
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_dropdown_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 280,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .mini-cart .cart-dropdown' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'amount_color',
			[
				'label' 		=> __( 'Amount Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .mini-cart .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cart_dropdown_bg_color',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .mini-cart .cart-dropdown' => 'background-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cart_dropdown_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .mini-cart .cart-dropdown',
				
				
			]
		);
		
		
		$this->add_control(
			'heading_button_checkout',
			[
				'label' => __( 'Checkout Button', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
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
					'{{WRAPPER}} .mini-cart .cart-dropdown .buttons .checkout' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .mini-cart .cart-dropdown .buttons .checkout' => 'background: {{VALUE}};',
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
					'{{WRAPPER}} .mini-cart .cart-dropdown .button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .mini-cart .cart-dropdown .button:hover' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mini-cart .cart-dropdown .button:hover' => 'border-color: {{VALUE}};',
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
				'name' => 'border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .mini-cart .cart-dropdown .buttons .checkout',								
			]
		);
	}
		
			
	protected function render() {
		$settings = $this->get_settings(); 	
		
		$column_switcher = $settings['column_switcher'];
		$breadcrumbs_on  = $settings['breadcrumbs'];
		$shop_filters    = $settings['shop_filters'];
		?>		
		
		<div class="bew-toolbar clr">
		
		<div class="shop-loop-head row">
					<div class="shop-display col-xl-7 col-lg-6">
						<?php woocommerce_result_count(); ?>
					</div>
					<div class="shop-filter col-xl-5 col-lg-6">

						<?php

						if ( ! $shop_filters ) :
							woocommerce_catalog_ordering();
							
						endif;

						if ( $column_switcher ) :

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
						<?php endif; ?>

						<?php if ( $shop_filters ) : ?>
							<div class="amely-filter-buttons">
								<a href="#" class="open-filters"><?php esc_html_e( 'Filters', 'briefcase-elementor-widgets' ); ?></a>
							</div><!-- .bew-filter-buttons -->
						<?php endif; ?>
					</div>
				</div><!--.shop-loop-head -->
				
				<?php if ( $shop_filters ) : ?>
					<div class="filters-area">
						<div class="filters-inner-area row">
							<?php dynamic_sidebar( 'filters-area' ); ?>
						</div><!-- .filters-inner-area -->
					</div><!--.filters-area-->

					<div
						class="active-filters"><?php the_widget( 'WC_Widget_Layered_Nav_Filters' ); ?></div><!--.active-filters-->
				<?php endif; ?>
				
		</div>		
		<?php
	}
	
}

Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_Woo_Shop_Toolbar() );