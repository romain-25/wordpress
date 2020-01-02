<?php
namespace Elementor;
/**
 * Woo Buy Now Module
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class BEW_Widget_Woo_Buy_Now extends Widget_Base {
	
	public function get_name() {
		return 'bew-woo-buy-now';
	}

	public function get_title() {
		return __( 'Woo Buy Now', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-product-add-to-cart';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];
	}
	
	public function get_script_depends() {
		return [ ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_buy_now',
			[
				'label' 		=> __( 'Buy Now', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'buy_now_type',
			[
				'label' 		=> __( 'Buy Now Button by ID', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
			]
		);
				
		$this->add_control(
			'buy_now_id',
			[
				'label' 		=> __( 'Buy Now ID', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder' 	=> __( 'Your Product ID', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'product_type' => 'yes',
                ]
					
			]
		);
				
		$this->add_control(
			'buy_now_text',
			[
				'label' => __( 'Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Buy Now', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Buy Now', 'briefcase-elementor-widgets' ),				
			]
		);		
		
		$this->add_control(
			'buy_now_icon',
			[
				'label' => __( 'Icon', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',				
			]
		);
		
		$this->add_control(
			'buy_now_icon_align',
			[
				'label' => __( 'Icon Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'briefcase-elementor-widgets' ),
					'right' => __( 'After', 'briefcase-elementor-widgets' ),
				],				
			]
		);

		$this->add_control(
			'buy_now_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} #bew-buy-now .bew-align-icon-right i' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} #bew-buy-now .bew-align-icon-left i' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_buy_now_redirect',
			[
				'label' => __( 'Redirect', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
				
			]
		);
		
		$this->add_control(
            'buy_now_redirect',
            [
                'label' => __('Redirect Location', 'briefcase-elementor-widgets'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'cart' => __( 'Cart', 'briefcase-elementor-widgets' ),
                    'checkout' => __( 'Checkout', 'briefcase-elementor-widgets' ),
					'custom' => __( 'Custom', 'briefcase-elementor-widgets' ),	
                ],                
                'default' => 'cart'

            ]
        );
		
		$this->add_control(
			'buy_now_custom_redirect',
			[
				'label' => __( 'Custom Redirect Location', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,				
				'placeholder' => __( 'https://yourdomain.com/custom-location', 'briefcase-elementor-widgets' ),
				'description' => __( 'Enter a full Url', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'buy_now_redirect' => 'custom',
				]
			]
		);	
		
		$this->add_control(
			'buy_now_show',
			[
				'label' => __( 'Show Buy Now Button for', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => true,
				'default' => 'all',
				'options' => [
					'all' => __( 'All Product Types', 'briefcase-elementor-widgets' ),
					'simple' => __( 'Simple', 'briefcase-elementor-widgets' ),
					'variable' => __( 'Variable', 'briefcase-elementor-widgets' ),
				],	
			]
		);
		
						
		$this->add_control(
			'heading_buy_now_underlines',
			[
				'label' => __( 'Underlines Button', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
				
			]
		);
		
		$this->add_control(
			'buy_now_underlines',
			[
				'label' 		=> __( 'Activate Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
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
					'{{WRAPPER}} #bew-buy-now' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
								
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_buy_now_style',
			[
				'label' => __( 'Buy Now', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'button_buy_now_typo',
				'selector' 		=> '{{WRAPPER}} #bew-buy-now .buy_now_button',
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_buy_now_style' );

		$this->start_controls_tab(
			'tab_button_buy_now_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'button_buy_now_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-buy-now .buy_now_button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn-underlines svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_buy_now_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-buy-now .buy_now_button' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_buy_now_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'button_buy_now_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-buy-now .buy_now_button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .btn-underlines:hover svg path' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'button_buy_now_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} #bew-buy-now .buy_now_button:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'button_buy_now_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} #bew-buy-now .buy_now_button:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->add_control(
			'hover_buy_now_animation',
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
				'selector' => '{{WRAPPER}} #bew-buy-now .buy_now_button',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'border_radius_buy_now',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-buy-now .buy_now_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_buy_now_box_shadow',
				'selector' => '{{WRAPPER}} #bew-buy-now .buy_now_button',
			]
		);
				
				
		$this->add_responsive_control(
			'button_buy_now_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-buy-now .buy_now_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',	
			]
		);
		
		$this->add_responsive_control(
			'button_buy_now_margin',
			[
				'label' => __( 'Button Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} #bew-buy-now .buy_now_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
		
        $this->end_controls_section();
	}
	
	protected function add_buy_now_button() {
		global $product;
		$args = array( 'product' => $product );
		echo $this->generate_button( $args );		
	}


	public function generate_button( $args ) {
		
		$settings = $this->get_settings();	
			
		$buy_now_show = $settings['buy_now_show'];
		$buy_now_text = $settings['buy_now_text'];
		$buy_now_icon = $settings['buy_now_icon'];
		
		$default_args = array(
			'product'         => null,
			'label'           => $buy_now_text,
			'class'           => ' bew-buy-now',
			//'hide_in_cart'    => true,
			//'hide_outofstock' => wc_qb_option( 'hide_outofstock' ),
			'tag'             => 'link',
		);

		$args     = wp_parse_args( $args, $default_args );
		$_arg_val = array( true, 'yes', '1', 1, 'on' );

		if ( in_array( $args['hide_in_cart'], $_arg_val, true ) ) {
			$args['hide_in_cart'] = 'yes';
		} else {
			$args['hide_in_cart'] = 'no';
		}

		extract( $args );
		$return = '';
				
		if ( $product == null ) {
			return;
		}
		$type = $product->get_type();
		if ( $buy_now_show == null ) {
			return;
		}
		if ( ! in_array( 'all', $buy_now_show ) && ! in_array( $type, $buy_now_show ) ) {
			return;
		}
		$pid = $product->get_id();
		$quantity  = 1;

		$defined_class = 'wc_buy_now_button buy_now_button buy_now_' . $type . ' buy_now_' . $pid . '_button buy_now_' . $pid . '' . $class;
		$defined_id    = 'buy_now_' . $pid . '_button';
		$defined_attrs = 'name=""  data-product-type="' . $type . '" data-product-id="' . $pid . '" data-quantity="' . $quantity . '"';

		if ( 'yes' === $args['hide_in_cart'] ) {
			$defined_attrs .= ' style="display:none;" ';
		}


		$return .= '<div class="buy_now_container buy_now_' . $pid . '_container" id="buy_now_' . $pid . '_container" >';

		if ( $tag == 'button' ) {
			$return .= '<input value="' . $label . '" type="button" id="' . $defined_id . '" ' . $defined_attrs . '  class="wcbn_button ' . $defined_class . '">';
		}

		if ( $tag == 'link' ) {
			$qty    = isset( $quantity ) ? $quantity : 1 ;
			$link   = $this->get_product_addtocartLink( $product, $qty );			
			$return .= '<a href="' . $link . '" id="' . $defined_id . '" ' . $defined_attrs . '  class="wcbn_button ' . $defined_class . '"><i class="' .$buy_now_icon . '" aria-hidden="true"></i>';
			$return .= $label;
			$return .= '</a>';
		}

		$return .= '</div>';
		return $return;
	}

	public function get_product_addtocartLink( $product, $qty = 1 ) {
		
		$redirect_url = $this->buy_now_redirect( $url );
		
		if ( $product->get_type() == 'simple' ) {
			$link = $redirect_url;			
			$link = add_query_arg( 'add-to-cart', $product->get_id(), $link );
			$link = add_query_arg( 'quantity', $qty, $link );		
			
			return $link;
		}
		return false;
	}
	
	/**
	 * Function to redirect user after buy now button is submitted
	 *
	 * @since   1.2.9
	 * @updated 1.2.9
	 * @return string [[Description]]
	 */
	public function buy_now_redirect( $url ) {
			
			$settings = $this->get_settings();	
			
			$redirect = $settings['buy_now_redirect'];
			$custom_redirect = $settings['buy_now_custom_redirect'];
			
			if ( $redirect == 'cart' ) {
				return wc_get_cart_url();
			} elseif ( $redirect == 'checkout' ) {
				return wc_get_checkout_url();
			} elseif ( $redirect == 'custom' && wc_notice_count( 'error' ) === 0 ) {				
				if ( ! empty( $buy_now_custom_redirect ) ) {
					wp_safe_redirect( $buy_now_custom_redirect );
					exit;
				}
			}
		
		return $url;
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
	
	protected function render() {
		
		$settings = $this->get_settings();
		
		$buy_now_by_id		 			 		= $settings['buy_now_by_id'];
		$buy_now_id_custom 	 					= $settings['buy_now_id'];
		$buy_now_icon_align 		    		= $settings['buy_now_icon_align'];		
		$buy_now_underlines						= $settings['buy_now_underlines'];
							
		// Inner classes
		$inner_classes 		= array( 'bew-buy-now');
		
		if ( 'yes' == $product_type) {
			$inner_classes[]  = 'button-buy-now-by-id';
		}		
		if ( '' == $product_addtocart_text ) {
			$inner_classes[]  = 'button-no-text';
		}		
				
		if ( 'yes' == $buy_now_underlines) {
			$inner_classes[]  = 'btn-underlines';		
		}
			$inner_classes[]  = 'bew-align-icon-' . $buy_now_icon_align;
						
		$inner_classes = implode( ' ', $inner_classes );		
			
			// Data for Bew Templates
				$this->product_data_loop();
					
			// Data for Elementor Pro Templates option
				global $product;
					
				if(is_string($product)){
					$product = wc_get_product();
				}
			// Buy Now by ID
			if ( 'yes' == $buy_now_by_id) { 
				if ($buy_now_id_custom != ''):
					$product_data = wc_get_product($buy_now_id_custom);
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
			} 
			 
            		
	// Buy Now underlines mode		
			if ( 'yes' == $buy_now_addtocart_underlines) {
					$svg  =	'<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="61" height="12" viewBox="0 0 61 12"><path d="';
					$html = 'M60.217,1.433 C45.717,2.825 31.217,4.217 16.717,5.609 C13.227,5.944 8.806,6.200 6.390,5.310 C7.803,4.196 11.676,3.654 15.204,3.216 C28.324,1.587 42.033,-0.069 56.184,0.335 C58.234,0.394 60.964,0.830 60.217,1.433 ZM50.155,5.670 C52.205,5.728 54.936,6.165 54.188,6.767 C39.688,8.160 25.188,9.552 10.688,10.943 C7.198,11.278 2.778,11.535 0.362,10.645 C1.774,9.531 5.647,8.988 9.175,8.551 C22.295,6.922 36.005,5.265 50.155,5.670 Z';
					$svg2 = '"></path></svg>';
					}	
		
	// Made the Buy Now button.		
		echo'<div id="bew-buy-now">';
		
		   // Button section
		   if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
					$product_id = $product->get_id();
					$product_type = $product->get_type();
				} else {
					$product_id = $product->id;
					$product_type = $product->product_type;
				}

				$class = implode( ' ', array_filter( [
					'button bew-element-woo-buy-now-btn',
					'product_type_' . $product_type,
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : 'out-of-stock',
					$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
				] ) );

		   
		   if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
			echo'<div class="'. esc_attr( $inner_classes ) .'">';	
			// add the buy now button					
			$this->add_buy_now_button();			
			
			echo '</div>';
			
		   }
		   
		echo '</div>';   
	   
	// JS for update the quantity data
	wc_enqueue_js( "
	
	
	jQuery( '#bew-cart .quantity .qty' ).on( 'change', function() {
		
		var qty = jQuery( this ).val(),
			buy_now = jQuery('.buy_now_container a'), 
			id = buy_now.data('product-id'),		
			new_qty = qty,
			old_qty = document.querySelector('.buy_now_container a').getAttribute('data-quantity'),		
			old_link = '?add-to-cart=' + id +'&quantity=' + old_qty,			
			new_link = '?add-to-cart=' + id + '&quantity=' + qty;	
		
		
		jQuery('.buy_now_container a').each(function(){
				this.href = this.href.replace(old_link, new_link);
		});
			
		jQuery( '.buy_now_container a' ).attr( 'data-quantity', new_qty);
		
	});
" );
	
		
	}
	
}

Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_Woo_Buy_Now() );