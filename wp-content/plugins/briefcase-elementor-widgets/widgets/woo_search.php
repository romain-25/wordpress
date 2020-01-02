<?php
/**
 * Woo Search Module
 */

namespace Elementor;


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



class BEW_Widget_Woo_Search extends Widget_Base {
	
	public function get_name() {
		return 'bew-woo-search';
	}

	public function get_title() {
		return __( 'Woo Search', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-search';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-search' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_woo_search',
			[
				'label' 		=> __( 'Woo Products Search', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_responsive_control(
            'search_style',
            [
                'label' => __( 'Search Style', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'icon' => [
						'title' => __( 'Icon', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-search',
					],
					'input' => [
						'title' => __( 'Input', 'briefcase-elementor-widgets' ),
						'icon'  => 'fa fa-pencil-square-o',
					]                   
                ],
                'default' => 'icon',
				'devices' => [ 'desktop', 'tablet', 'mobile' ],	
				'prefix_class' => 'search-style-%s-'
            ]
        );
		
				
		$this->add_control(
			'search_by',
			[
				'label' 		=> __( 'Search by', 'briefcase-elementor-widgets' ),
				'description' 	=> __( 'You can select multiples options', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT2,
				'options' => [
					'title'   => __( 'Title', 'briefcase-elementor-widgets' ),
					'excerpt' => __( 'Excerpt', 'briefcase-elementor-widgets' ),
					'sku' 	  => __( 'SKU', 'briefcase-elementor-widgets' ),
				],
				'default' => 'title',
				'label_block' 	=> true,
				'multiple' => true,
								
			]
		);
		
		$this->add_responsive_control(
			'search_categories',
			[
				'label' 		=> __( 'Categories', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'tablet_default' 		=> 'yes',
				'mobile_default' 		=> 'yes',				
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
				'prefix_class' => 'categories-%s-'
			]
		);
		
		$this->add_control(
			'search_excerpt',
			[
				'label' 		=> __( 'Show Excerpt', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
			]
		);
		
						
		$this->add_control(
			'search_ajax',
			[
				'label' 		=> __( 'Ajax Search', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
				'search_style' => 'input',
				],
			]
		);
		
		$this->add_control(
			'search_input_fullwidth',
			[
				'label' 		=> __( 'Fullwidth Results', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'fullwidth',
				'condition' => [
				'search_style' => 'input',
				],
			]
		);
		
		
		$this->add_control(
			'search_submit_button',
			[
				'label' 		=> __( 'Submit Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'has-submit-button',
				'condition' => [
				'search_style' => 'input',
				],
			]
		);
		
		$this->add_control(
			'search_submit_button_text',
			[
				'label' => __( 'Button Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Search', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Search', 'briefcase-elementor-widgets' ),	
				'label_block' => true,
				'condition' => [
				'search_style' => 'input',
				'search_submit_button' => 'has-submit-button',
				],
			]
		);
		
		$this->add_control(
			'search_submit_button_icon',
			[
				'label' 		=> __( 'Button Icon', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
				'search_style' => 'input',
				'search_submit_button' => 'has-submit-button',
				],
			]
		);
		
		$this->add_responsive_control(
			'search_description_section',
			[
				'label' 		=> __( 'Search Description', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'separator' => 'before',	
				'prefix_class' => 'search-description-%s-'
			]
		);
				
		$this->add_responsive_control(
			'search_min_chars',
			[
				'label' => __( 'Minimum Character', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '1',				
				'label_block' 	=> true,
				'condition' => [
				'search_description_section' => 'yes',				
				],
			]
		);
		
		$this->add_responsive_control(
			'search_limit',
			[
				'label' => __( 'Limit Products', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '6',
				'label_block' 	=> true,
				'condition' => [
				'search_description_section' => 'yes',				
				],
			]
		);
		
		$this->add_control(
			'search_description',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( '# Type at least 1 character to search # Hit enter to search or ESC to close', 'briefcase-elementor-widgets' ),
				'show_label' => true,	
				'condition' => [
				'search_description_section' => 'yes',				
				],
			]
		);
		
		$this->add_control(
			'search_placeholder',
			[
				'label' => __( 'Search Placeholder', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Search Products...', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Search Products...', 'briefcase-elementor-widgets' ),				
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
					'{{WRAPPER}} .bew-woo-search' => 'text-align: {{VALUE}};',
				],
			]
		);
								
		$this->end_controls_section();
		
				
		$this->start_controls_section(
			'section_style_icon_search',
			[
				'label' => __( 'Icon Button', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
				'search_style' => 'icon',
				],
			]
		);
				
		$this->add_control(
			'search_icon_type',
			[
				'label' => __( 'Icon Type', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'briefcase-elementor-widgets' ),
					'bold' => __( 'Bold', 'briefcase-elementor-widgets' ),
				],	
				'condition' => [
				'search_style' => 'icon',
				],
			]

		);
		
		$this->add_responsive_control(
			'icon_size',
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
					'size' => 18,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .header-search>a.toggle' => 'font-size: {{SIZE}}{{UNIT}};',
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
				'selectors' 	=> [
					'{{WRAPPER}} .header-search a.toggle' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .header-search a:hover.toggle' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .bew-woo-search .header-search' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'icon_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-search .header-search' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector' => '{{WRAPPER}} .bew-woo-search .header-search',
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
					'{{WRAPPER}} .bew-woo-search .header-search' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
				
		$this->start_controls_section(
			'section_search_form',
			[
				'label' 		=> __( 'Search Form', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'search_form_typography',				
				'selector' => '.search-style-input .search-form-wrapper input.search-input',
			]
		);
		
		$this->add_control(
			'search_form_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-form-wrapper input.search-input, .search-form-wrapper .select2-container--default .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'search_form_color_bg',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					' .bew-woo-search-form .search-form-wrapper input.search-input' => 'color: {{VALUE}};',
				],
			]
		);
		
		
		$this->add_control(
			'search_form_color_placeholder',
			[
				'label' 		=> __( 'Placeholder Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					' input.search-input::-webkit-input-placeholder, input.search-input::placeholder' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'search_form_input_width',
			[
				'label' => __( 'Search Input Width', 'briefcase-elementor-widgets' ),
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
					'.search-form-wrapper input.search-input' => 'width: {{SIZE}}{{UNIT}} !important;',
				],				
			]
		);
				
		$this->add_responsive_control(
			'search_categories_size',
			[
				'label' => __( 'Categories Input Width', 'briefcase-elementor-widgets' ),
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
					'size' => 28,					
				],
				'size_units' => [ '%' , 'px'],
				'selectors' => [
					'.search-style-input .search-form-wrapper .has-categories-select .select2, search-form-wrapper .has-categories-select .select2' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
				'search_categories' => 'yes',
				],
			]
		);	
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'search_form_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '.search-form-wrapper input.search-input, .search-form-wrapper .select2, .search-style-input .search-form-wrapper #search-btn',
			]
		);
		
		$this->add_control(
			'search_form_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'search_form_border_border!' => '',
				],
				'selectors' => [
					'.search-form-wrapper input.search-input, .search-form-wrapper .select2, .search-style-input .search-form-wrapper #search-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_control(
			'search_form_border_focus',
			[
				'label' 		=> __( 'Border Color Focus', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'condition' => [
					'search_form_border_border!' => '',
				],
				'selectors' 	=> [
					' .search-form-wrapper form input[type="text"]:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'heading_products_categories',
			[
				'label' => __( 'Categories', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
				'search_categories' => 'yes',
				],
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'products_categories_selected',
			[
				'label' 		=> __( 'Selected Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.select2-container--default .select2-results__option--highlighted[aria-selected]' => 'color: {{VALUE}};',
				],
				'condition' => [
				'search_categories' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'products_categories_selected_bg',
			[
				'label' 		=> __( 'Selected Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.select2-container--default .select2-results__option--highlighted[aria-selected]' => 'background-color: {{VALUE}};',
				],
				'condition' => [
				'search_categories' => 'yes',
				]
			]
		);
		
		$this->add_control(
			'heading_search_button',
			[
				'label' => __( 'Search Button', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->start_controls_tabs( 'tabs_search_button_style' );

		$this->start_controls_tab(
			'tab_search_button_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'search_button_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-style-input .search-form-wrapper #search-btn' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'search_button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-style-input .search-form-wrapper #search-btn' => 'background: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_search_button_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'search_button_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-style-input .search-form-wrapper #search-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'search_button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-style-input .search-form-wrapper #search-btn:hover' => 'background-color: {{VALUE}};',
				],
				
			]
		);

			
		$this->add_control(
			'search_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'search_button_border_border!' => '',
				],
				'selectors' => [
					'.search-style-input .search-form-wrapper #search-btn:hover' => 'border-color: {{VALUE}};',
				],
				
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'search_button_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '.search-style-input .search-form-wrapper #search-btn',				
				
			]
		);
		
		$this->add_control(
			'search_button_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.search-style-input .search-form-wrapper #search-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'search_button_padding',
			[
				'label' => __( 'Text Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.search-style-input .search-form-wrapper #search-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				
			]
		);
		
		$this->add_responsive_control(
			'search_button_margin',
			[
				'label' => __( 'Text Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'.search-style-input .search-form-wrapper #search-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
					
			]
		);
						
		$this->add_control(
			'heading_products_results',
			[
				'label' => __( 'Search Results', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'title_products_color',
			[
				'label' 		=> __( 'Product Title Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-form-wrapper .search-results-wrapper .suggestion-title' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'detail_products_color',
			[
				'label' 		=> __( 'Product Details Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'.search-form-wrapper .search-results-wrapper .suggestion-price, .search-form-wrapper .search-results-wrapper .suggestion-sku' => 'color: {{VALUE}}; border-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_control(
			'price_products_color',
			[
				'label' 		=> __( 'Product Price Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'.search-form-wrapper .search-results-wrapper .suggestion-price .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'search_form_products_bg',
			[
				'label' 		=> __( 'Hover Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'.search-form-wrapper .search-results-wrapper .autocomplete-suggestion.autocomplete-selected' => 'background-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_responsive_control(
			'search_results_size_w',
			[
				'label' => __( 'Results Width', 'briefcase-elementor-widgets' ),
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
					'.search-style-input .search-results-wrapper, .search-results-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'search_input_fullwidth' => '',
				],
			]
		);

		$this->add_responsive_control(
			'search_results_size_h',
			[
				'label' => __( 'Results Height', 'briefcase-elementor-widgets' ),
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
					'unit' => 'px',									
				],
				'size_units' => [ '%' , 'px'],
				'selectors' => [
					'.search-style-input .search-form-wrapper .search-results-wrapper .autocomplete-suggestions, .search-style-input.fullwidth  .search-form-wrapper .search-results-wrapper .autocomplete-suggestions, .search-style--icon .search-form-wrapper .search-results-wrapper .autocomplete-suggestions ' => 'max-height: {{SIZE}}{{UNIT}} !important;',
				],				
			]
		);
				
		$this->add_responsive_control(
			'search_form_products_padding',
			[
				'label' 		=> __( 'Padding Product', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'.search-form-wrapper .search-results-wrapper .autocomplete-suggestion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'search_form_products_margin',
			[
				'label' 		=> __( 'Margin Products', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'.search-form-wrapper .search-results-wrapper .autocomplete-suggestion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();	
		
		
	}
	
	/**
	* Creates the WooCommerce Search module
	*
	* @since 1.2.0
	*/
		
	public function search_form() {
		
			$settings = $this->get_settings(); 	
			
					
						
			$search_submit_button_text  = $settings['search_submit_button_text'];
			$search_submit_button_icon  = $settings['search_submit_button_icon'];
			
			$search_result 				= $settings['search_input_fullwidth'];
						
			$post_type     = 'product';
			if($settings['search_categories'] == 'yes'){
			$categories_on = true;	
			}
			if($settings['search_ajax'] == 'yes'){
			$ajax_search   = true;
			}
						
			$min_chars 	   = $settings['search_min_chars'];

			$classes = array( 'search-form' );

			if ( isset($categories_on) ) {
				$classes[] = ' has-categories-select';
			}

			if ( isset($ajax_search) ) {
				$classes[] = ' ajax-search-form';
			}
			
			if ( 'yes' == $search_submit_button_icon ) {
				$classes[] = 'submit-button-icon';
			}
			

			$place_holder = esc_html__( $settings['search_placeholder'], 'briefcase-elementor-widgets' );
			$description = esc_html__( $settings['search_description'], 'briefcase-elementor-widgets' );

			if ( $post_type == 'post' ) {
				$place_holder = esc_html__( 'Search Posts...', 'briefcase-elementor-widgets' );
			}
			?>
			
			<div class="bew-woo-search-container <?php echo  $search_result; ?>">
			
			<div class="bew-woo-search-form search-form-wrapper">
				<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search"
				      class="<?php echo implode( ' ', $classes ); ?>">
					<input name="s" class="search-input" type="text" value="<?php echo get_search_query() ?>"
					       placeholder="<?php echo esc_attr( $place_holder ); ?>" autocomplete="off"/>
					<input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>"/>
					<?php if ( isset($categories_on) ) {
						$args = array(
							'show_option_all' => esc_html__( 'All Categories', 'briefcase-elementor-widgets' ),
							'hierarchical'    => 1,
							'class'           => 'search-select',
							'echo'            => 1,
							'value_field'     => 'slug',
							'selected'        => 1,
						);

						$search_child_cats = apply_filters( 'bew_search_child_cats', true );
						if ( ! $search_child_cats ) {
							$args['parent'] = 0;
						}

						if ( class_exists( 'WooCommerce' ) && 'product' == $post_type ) {
							$args['taxonomy'] = 'product_cat';
							$args['name']     = 'product_cat';

							wp_dropdown_categories( $args );
						} else {
							wp_dropdown_categories( $args );
						}
					} ?>
					<button type="submit" id="search-btn"
					        title="<?php esc_attr_e( 'Search', 'briefcase-elementor-widgets' ); ?>"><i class="ti-search" aria-hidden="true"></i><?php esc_html_e( $search_submit_button_text,
							'briefcase-elementor-widgets' ); ?></button>
				</form>
				<p class="search-description">
					<?php
					if(empty($description)){ ?>
						<span><?php echo sprintf( esc_html__( '# Type at least %s %s to search', 'briefcase-elementor-widgets' ),
								$min_chars,
								_n( 'character', 'characters', $min_chars, 'briefcase-elementor-widgets' ) ); ?></span>
						<span><?php esc_html_e( '# Hit enter to search or ESC to close', 'briefcase-elementor-widgets' ); ?></span>	
					<?php } else { ?>						
						<span><?php echo esc_html_e( $description , 'briefcase-elementor-widgets' ); ?></span>	
					<?php }	?>
					
				</p>
				<div class="search-results-wrapper">
					<p class="ajax-search-notice"></p>
				</div>
				<div class="btn-search-close btn--hidden">
					<i class="pe-7s-close"></i>
				</div>
			</div>
			</div>
			<?php
			
	}
	
	
				
	protected function render() {
		$settings = $this->get_settings(); 	
		
		$search_icon_type 			= $settings['search_icon_type'];
		$search_min_chars 			= $settings['search_min_chars'];			
		$search_limit	  			= $settings['search_limit'];					
		$search_style 				= $settings['search_style'];
		$search_result 				= $settings['search_input_fullwidth'];	
		$search_submit_button 		= $settings['search_submit_button'];
		$search_by 					= $settings['search_by'];
		$search_excerpt 			= $settings['search_excerpt'];
		
		// Wrapper search classes
			$wrap_classes_search = array( '' );
						
			$wrap_classes_search[] = esc_attr( $search_submit_button );
						
			$wrap_classes_search = implode( ' ', $wrap_classes_search );
			
		//Icon style

		if($search_icon_type == 'light'){
		$search_icon = 'ti-search';	
		}elseif($search_icon_type == 'bold'){
		$search_icon = 'fa fa-search';		
		}
		
		// WooCommerce Search
		?>						
			<div class="bew-woo-search">
				<div class="header-search <?php echo esc_attr( $wrap_classes_search ); ?>">
					<?php 					
						echo $this->search_form();					
					?>
						<a href="#" class="toggle">
							<i class="<?php echo $search_icon; ?>" aria-hidden="true"></i>
							<span><?php esc_attr_e( 'Search', 'briefcase-elementor-widgets' ) ?></span>
						</a>					
				</div>							
			</div>
		<?php										
						
		// Enqueue Woo Search JS
				
		$ajax_url     			= admin_url( 'admin-ajax.php' );			
		
			wp_localize_script( 'woo-search',
				'woosearchConfigs',
				array(
					'ajax_url'                         => esc_url( $ajax_url ),									
					'search_by'                        => $search_by,
					'search_min_chars'                 => $search_min_chars,
					'search_limit'                     => $search_limit,
					'search_excerpt_on'                => $search_excerpt == 'yes' ? true : false,
					
				) );
	}
	
}

Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_Woo_Search() );