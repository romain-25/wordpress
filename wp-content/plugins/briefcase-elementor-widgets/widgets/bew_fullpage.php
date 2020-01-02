<?php
/**
 * Bew Fullpage Module
 */

namespace Elementor;

use Elementor;
use Elementor\Plugin;
use Elementor\Post_CSS_File;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEW_Widget_fullpage extends Widget_Base {

	public function get_name() {
		return 'bew-fullpage';
	}

	public function get_title() {
		return __( 'BEW Fullpage', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-slides';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];

	}
	
	public function is_reload_preview_required() {
		return true;
	}

	
	public function get_script_depends() {
		return [ 'imagesloaded', 'jquery-slimscroll', 'jquery-easings', 'jquery-pseudo', 'scrolloverflow', 'jquery-fullpage-parallax', 'jquery-fullpage', 'global','woo-grid','fullpage-menu', 'bew-fullpage'  ];
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
	
	public static function responsive_message() {
		return '<div id="bew-message">				
				<div class="responsive-message-title" style="margin-bottom:10px;">' . __( 'Turn to normal scroll when the window size gets smaller than X Width or X Height.', 'briefcase-elementor-widgets' ) . '</div>
				<div class="responsive-message-desc" style="margin-bottom:10px;">' . __( 'Example for mobile device use <b>480px</b> (Width) and/or <b>568px</b> (Height).', 'briefcase-elementor-widgets' ) .'</div>
				<div class="responsive-message-footer" >' . __( '<span style="font-weight:bold; color: red;">Important:</span> Use <b>0px</b> to disable the responsive option.' , 'briefcase-elementor-widgets' ) .'</div>
				</div>';
	}


	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'briefcase-elementor-widgets' ),
			'sm' => __( 'Small', 'briefcase-elementor-widgets' ),
			'md' => __( 'Medium', 'briefcase-elementor-widgets' ),
			'lg' => __( 'Large', 'briefcase-elementor-widgets' ),
			'xl' => __( 'Extra Large', 'briefcase-elementor-widgets' ),
		];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_fullpage',
			[
				'label' 		=> __( 'Fullpage General', 'briefcase-elementor-widgets' ),
			]
		);

		$repeater = new Repeater();
		
		$repeater->add_control(
			'section_name',
			[
				'label' => __( 'Section Name', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,				
				'label_block' => true,				
			]
		);
		
		$repeater->add_control(
			'h_slider',
			[
				'label' 		=> __( 'Slide', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'description' => __( 'Convert to horizontal slide', 'briefcase-elementor-widgets' ),
			]
		);
		
		$repeater->add_control(
			'h_slider_parent',
			[
				'label' 		=> __( 'Parent Slide', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
                    'h_slider' => 'yes',
				],
				'description' => __( 'Set this section a parent slide', 'briefcase-elementor-widgets' ),
			]
		);
		
		$repeater->start_controls_tabs( 'slides_repeater');
		
		$repeater->start_controls_tab( 'background', [ 'label' => __( 'Background', 'briefcase-elementor-widgets' ) ] );
				
		$repeater->add_control(
			'background_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-bg' => 'background-color: {{VALUE}}',
				],				
			]
		);

		$repeater->add_control(
			'background_image',
			[
				'label' => _x( 'Image', 'Background Control', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::MEDIA,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-bg' => 'background-image: url({{URL}})',
				],				
			]
		);

		$repeater->add_control(
			'background_size',
			[
				'label' => _x( 'Size', 'Background Control', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'cover' => _x( 'Cover', 'Background Control', 'briefcase-elementor-widgets' ),
					'contain' => _x( 'Contain', 'Background Control', 'briefcase-elementor-widgets' ),
					'auto' => _x( 'Auto', 'Background Control', 'briefcase-elementor-widgets' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-bg' => 'background-size: {{VALUE}}',
				],				
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],						
					],
				   
				],
			]
		);

		$repeater->add_control(
			'background_ken_burns',
			[
				'label' => __( 'Ken Burns Effect', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],						
					],
				],
			]
		);

		$repeater->add_control(
			'zoom_direction',
			[
				'label' => __( 'Zoom Direction', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'in',
				'options' => [
					'in' => __( 'In', 'briefcase-elementor-widgets' ),
					'out' => __( 'Out', 'briefcase-elementor-widgets' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_ken_burns',
							'operator' => '!=',
							'value' => '',
						],						
					],				
				],
			]
		);

		$repeater->add_control(
			'background_overlay',
			[
				'label' => __( 'Background Overlay', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_image[url]',
							'operator' => '!=',
							'value' => '',
						],						
					],				
				],
			]
		);

		$repeater->add_control(
			'background_overlay_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.5)',
				'conditions' => [
					'terms' => [
						[
							'name' => 'background_overlay',
							'operator' => '==',
							'value' => 'yes',
						],						
					],					
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner .bew-background-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'content', [ 'label' => __( 'Content', 'briefcase-elementor-widgets' ),
			'condition' => [
                    'custom_template!' => 'yes',
				] ] );

		$repeater->add_control(
			'heading',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Heading', 'briefcase-elementor-widgets' ),
				'label_block' => true,
				'condition' => [
                    'custom_template!' => 'yes',
				]
			]
		);
		
		$repeater->add_control(
			'subheading',
			[
				'label' => __( 'Sub-Title', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Sub-Heading', 'briefcase-elementor-widgets' ),
				'label_block' => true,
				'condition' => [
                    'custom_template!' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'briefcase-elementor-widgets' ),
				'show_label' => true,
				'condition' => [
                    'custom_template!' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label' => __( 'Button Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Click Here', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'custom_template!' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://your-link.com', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'custom_template!' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'link_click',
			[
				'label' => __( 'Apply Link On', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'slide' => __( 'Whole Slide', 'briefcase-elementor-widgets' ),
					'button' => __( 'Button Only', 'briefcase-elementor-widgets' ),
				],
				'default' => 'slide',
				'conditions' => [
					'terms' => [
						[
							'name' => 'link[url]',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'custom_template',
							'operator' => '!=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'style', [ 'label' => __( 'Style', 'briefcase-elementor-widgets' ),
			'condition' => [
                    'custom_template!' => 'yes',
				] ] );

		$repeater->add_control(
			'custom_style',
			[
				'label' => __( 'Custom', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'description'   => __( 'Set custom style that will only affect this specific slide.', 'briefcase-elementor-widgets' ),
				'condition' => [
                    'custom_template!' => 'yes',
				]
			]
		);

		$repeater->add_control(
			'horizontal_position',
			[
				'label' => __( 'Horizontal Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner .bew-slide-content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'margin-right: auto',
					'center' => 'margin: 0 auto',
					'right' => 'margin-left: auto',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'custom_template',
							'operator' => '!=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'vertical_position',
			[
				'label' => __( 'Vertical Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'custom_template',
							'operator' => '!=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner' => 'text-align: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'custom_template',
							'operator' => '!=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'content_color',
			[
				'label' => __( 'Content Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner .bew-slide-heading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner .bew-slide-subheading' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner .bew-slide-description' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .fullpage-slide-inner .bew-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'custom_style',
							'operator' => '==',
							'value' => 'yes',
						],
						[
							'name' => 'custom_template',
							'operator' => '!=',
							'value' => 'yes',
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();
		
		$repeater->add_control(
			'heading_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
			'auto_height',
			[
				'label' 		=> __( 'Auto Height', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'description' => __( 'Use the original height section', 'briefcase-elementor-widgets' ),
			]
		);
		
		$repeater->add_control(
			'auto_height_responsive',
			[
				'label' 		=> __( 'Auto Height Responsive', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'description' => __( 'Use the original responsive height section (Note: It only works with the responsive section option enabled) ', 'briefcase-elementor-widgets' ),
				
			]
		);
		
		$repeater->add_control(
			'heading_template',
			[
				'label' => __( 'Template', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$repeater->add_control(
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

			$repeater->add_control(
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

		
		} else {

		$options = [
			'0' => '— ' . __( 'Select', 'briefcase-elementor-widgets' ) . ' —',
		];

		$types = [];

		foreach ( $templates as $template ) {
			$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			$types[ $template['template_id'] ] = $template['type'];
		}

		$repeater->add_control(
			'template_id',
			[
				'label' => __( 'Choose Template', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $options,
				'types' => $types,
				'label_block'  => 'true',
				'condition' => [
                    'custom_template' => 'yes',
				]
			]
		);
		
		}
		
		
		$this->add_control(
			'slides',
			[
				'label' => __( 'Fullpage Sections', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				'default' => [
					[
						'section_name' => __( 'Fullpage Section 1', 'briefcase-elementor-widgets' ),
						'heading' => __( 'Fullpage Heading 1', 'briefcase-elementor-widgets' ),
						'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'briefcase-elementor-widgets' ),
						'button_text' => __( 'Click Here', 'briefcase-elementor-widgets' ),
						'background_color' => '#00BAF2',
					],
					[
						'section_name' => __( 'Fullpage Section 2', 'briefcase-elementor-widgets' ),
						'heading' => __( 'Fullpage Heading 2', 'briefcase-elementor-widgets' ),
						'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'briefcase-elementor-widgets' ),
						'button_text' => __( 'Click Here', 'briefcase-elementor-widgets' ),
						'background_color' => '#DC3545',
					],
					[
						'section_name' => __( 'Fullpage Section 3', 'briefcase-elementor-widgets' ),
						'heading' => __( 'Fullpage Heading 3', 'briefcase-elementor-widgets' ),
						'description' => __( 'Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'briefcase-elementor-widgets' ),
						'button_text' => __( 'Click Here', 'briefcase-elementor-widgets' ),
						'background_color' => '#FFC107',
					],
				],
				'fields' => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ section_name }}}',
			]
		);

		$this->add_responsive_control(
			'slides_height',
			[
				'label' => __( 'Height', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1500,
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
					'{{WRAPPER}} .fullpage-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
                    'slide_type' => 'horizontal',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => __( 'Fullpage Options', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SECTION,
			]
		);
		
		$this->add_control(
			'transition',
			[
				'label' => __( 'Transition', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'briefcase-elementor-widgets' ),
					'parallax' => __( 'Parallax', 'briefcase-elementor-widgets' ),
				],
				
			]
		);
		
		$this->add_control(
			'slide_type',
			[
				'label' => __( 'Slide Style', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => [
					'vertical' => __( 'Scroll Vertically', 'briefcase-elementor-widgets' ),
					'horizontal' => __( 'Scroll Horizontally', 'briefcase-elementor-widgets' ),
				],
				'condition' => [
                    'transition' => 'slide',
				]
			]
		);
		
		$this->add_control(
			'parallax_extension',
			[
				'label' => __( 'Parallax Extension', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
                    'transition' => 'parallax',
				]
			]
		);
		
		$this->add_control(
			'parallax_notice',
			[
				'raw' => __( 'IMPORTANT: The parallax transition only works if you have the Fullpage Parallax Extension.', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition' => [				    
					'transition' => 'parallax',
				],
			]
		);
		
		
		
		$this->add_control(
			'heading_navigation',
			[
				'label' => __( 'Navigation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'navigation_dots',
			[
				'label' => __( 'Vertical Dots', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',				
				'condition' => [
                    'slide_type' => 'vertical',
				]
			]
		);
		
		$this->add_control(
			'navigation_dots_position',
			[
				'label' => __( 'Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'right' => __( 'Right', 'briefcase-elementor-widgets' ),
					'left' => __( 'Left', 'briefcase-elementor-widgets' ),					
				],
				'condition' => [
                    'slide_type' => 'vertical',
				]
			]
		);
		
		$this->add_control(
			'navigation_horizontal',
			[
				'label' => __( 'Horizontal Dots', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'navigation_horizontal_arrows',
			[
				'label' => __( 'Arrows', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',			
			]
		);
		
		$this->add_control(
			'navigation_anchors',
			[
				'label' => __( 'Anchors', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',			
			]
		);

		$this->add_control(
			'scrolling_speed',
			[
				'label' => __( 'Scrolling Speed', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 700,
			]
		);

		$this->add_control(
			'heading_animations_videos',
			[
				'label' => __( 'Animations and Videos', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'reset_animation',
			[
				'label' => __( 'Reset Sections Animation Mode', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'description' => __( 'Use this Mode to reset the elementor animations when you scrolling back or revisiting the section.', 'briefcase-elementor-widgets' ),
			]
		);
				
		$this->add_control(
			'keep_playing_video',
			[
				'label' => __( 'Keep Playing Video', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',				
			]
		);
		
		$this->add_control(
			'scrollOverflow',
			[
				'label' => __( 'Scroll Overflow', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
				'description' => __( 'Use ScrollOverflow to create a scroll for the section in case its content is bigger than the window height.', 'briefcase-elementor-widgets' ),
			]
		);
				
		$this->add_control(
			'responsivesections',
			[
				'label' => __( 'Responsive Sections', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
			]
		);
				
		$this->add_control(
			'responsiveWidth',
			[
				'label' => __( 'Responsive Width(px)', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 480,
				'condition' => [
                    'responsivesections' => 'yes',
				]
				
			]
		);
				
		$this->add_control(
			'responsiveHeight',
			[
				'label' => __( 'Responsive Height(px)', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 568,
				'condition' => [
                    'responsivesections' => 'yes',
				]
				
			]
		);
				
		$this->add_control(
			'responsiveHeight_desc',
			[
				'label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => $this->responsive_message(),					
			]
		);
		
		
		
		$this->add_control(
			'callback_afterLoad',
			[
				'label' => __( 'AfterLoad Callback', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( '', 'briefcase-elementor-widgets' ),
				'show_label' => true,
				'separator' => 'before',			
			]
		);
		
		$this->add_control(
			'callback_afterLoad_desc',
			[
				'label' => __( 'Write your own custom afterLoad callback. This callback fired once the sections have been loaded, after the scrolling has ended. Parameters:(anchorLink, index). ', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::RAW_HTML,
				
			]
		);
		
		
		$this->add_control(
			'callback_onleave',
			[
				'label' => __( 'Onleave Callback', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( '', 'briefcase-elementor-widgets' ),
				'show_label' => true,
				'separator' => 'before',			
			]
		);
		
		$this->add_control(
			'callback_onleave_desc',
			[
				'label' => __( 'Write your own custom onleave callback. This callback is fired once the user leaves a section, in the transition to the new section. Parameters:(index, nextIndex, direction) ', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::RAW_HTML,
				
			]
		);
		
		$this->add_control(
			'callback_desc',
			[
				'label' => __( 'To use custom callbacks on your section, check the BEW Fullpage <a class="bew-widget-fullpage-documentation-url" href="https://briefcasewp.com/bew-fullpage-documentation/" target="_blank">documentation</a>.', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::RAW_HTML,
				
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_menu_options',
			[
				'label' => __( 'Menu Options', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SECTION,
			]
		);
		
		$this->add_control(
			'menu_show',
			[
				'label' => __( 'Hide Menu on Scroll Down', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => '',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_fullpages',
			[
				'label' => __( 'Fullpages', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,				
			]
		);

		$this->add_responsive_control(
			'content_max_width',
			[
				'label' => __( 'Content Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'size' => '66',
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-slide-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'fullpages_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .fullpage-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'fullpages_horizontal_position',
			[
				'label' => __( 'Horizontal Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'bew--h-position-',
			]
		);

		$this->add_control(
			'fullpages_vertical_position',
			[
				'label' => __( 'Vertical Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'middle',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'prefix_class' => 'bew--v-position-',
			]
		);

		$this->add_control(
			'fullpages_text_align',
			[
				'label' => __( 'Text Align', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
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
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .fullpage-slide-inner' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,				
			]
		);

		$this->add_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fullpage-slide-inner .bew-slide-heading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'heading_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-heading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bew-slide-heading',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_subtitle',
			[
				'label' => __( 'Sub Title', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,				
			]
		);
		
		$this->add_control(
			'subheading_position',
			[
				'label' => __( 'Position', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'after',
				'options' => [
					'before' => [
						'title' => __( 'Before', 'briefcase-elementor-widgets' ),
						'title' => __( 'Top', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-top',
					],
					'after' => [
						'title' => __( 'After', 'briefcase-elementor-widgets' ),
						'icon' => 'eicon-v-align-bottom',
					],					
				],				
			]
		);
		
		
		$this->add_control(
			'subheading_spacing',
			[
				'label' => __( 'Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fullpage-slide-inner .bew-slide-subheading:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'subheading_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-subheading' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subheading_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bew-slide-subheading',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_description',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_spacing',
			[
				'label' => __( 'Spacing', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .fullpage-slide-inner .bew-slide-description:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-description' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .bew-slide-description',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => __( 'Button', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,				
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_control( 'button_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button' => 'color: {{VALUE}}; border-color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),
				'selector' => '{{WRAPPER}} .bew-slide-button',
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
			]
		);

		$this->add_control(
			'button_border_width',
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
					'{{WRAPPER}} .bew-slide-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => __( 'Normal', 'briefcase-elementor-widgets' ) ] );

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover', [ 'label' => __( 'Hover', 'briefcase-elementor-widgets' ) ] );

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-slide-button:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => __( 'Navigation', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,				
			]
		);

		$this->add_control(
			'heading_style_dots',
			[
				'label' => __( 'Dots', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'navigation_dots' => 'yes',
				],
				'separator' => 'before',				
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => __( 'Dots Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 15,
					],
				],
				'selectors' => [
					' #fp-nav ul li, #fp-nav ul li a span,#fp-nav ul li a.active span, #fp-nav ul li:hover a.active span,#fp-nav ul li:hover a span, .fp-slidesNav ul li, .fp-slidesNav ul li a span, .fp-slidesNav ul li a.active span, .fp-slidesNav ul li:hover a.active span, .fp-slidesNav ul li:hover a span' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'dots_color',
			[
				'label' => __( 'Dots Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'#fp-nav ul li a span, .fp-slidesNav ul li a span' => 'background: {{VALUE}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'dots_color_active',
			[
				'label' => __( 'Dots Active Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'#fp-nav ul li a.active span, #fp-nav ul li:hover a.active span, .fp-slidesNav ul li a.active span, .fp-slidesNav ul li:hover a.active span' => 'background: {{VALUE}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],	
			]
		);
		
		$this->add_control(
			'dots_color_hover',
			[
				'label' => __( 'Dots Hover Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'#fp-nav ul li:hover a span, .fp-slidesNav ul li:hover a span' => 'background: {{VALUE}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'dots_separation',
			[
				'label' => __( 'Dots Separation', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],
				'default' => [
					'size' => '21',
					'unit' => 'px',
				],				
				'selectors' => [
					'#fp-nav ul li' => 'margin-top: {{SIZE}}{{UNIT}}',
					'.fp-slidesNav ul li' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'heading_style_tooltips',
			[
				'label' => __( 'Tooltips', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tooltips_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),				
				'selector' => '#fp-nav ul li .fp-tooltip',
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'tooltips_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'#fp-nav ul li .fp-tooltip' => 'color: {{VALUE}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],	
			]
		);
		
		$this->add_control(
			'tooltips_color_bg',
			[
				'label' => __( 'Background', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'#fp-nav ul li .fp-tooltip' => 'background: {{VALUE}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'tooltips_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '#fp-nav ul li .fp-tooltip',			
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'tooltips_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'#fp-nav ul li .fp-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
				
		$this->add_responsive_control(
			'tooltips_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'#fp-nav ul li .fp-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_dots' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'heading_style_arrows',
			[
				'label' => __( 'Arrows', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'arrows_size',
			[
				'label' => __( 'Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,					
				],
				'size_units' => ['px'],
				'selectors' => [
					'.fp-controlArrow.fp-prev' => 'font-size:{{SIZE}}{{UNIT}};',
					'.fp-controlArrow.fp-next' => 'font-size:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'.fp-controlArrow.fp-prev' => 'color:{{VALUE}}',
					'.fp-controlArrow.fp-next' => 'color:{{VALUE}}',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'arrows_color_background',
			[
				'label' => __( 'Background', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'.fp-controlArrow.fp-prev' => 'background-color:{{VALUE}}',
					'.fp-controlArrow.fp-next' => 'background-color:{{VALUE}}',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'arrows_border_width',
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
					'{{WRAPPER}}  .fp-controlArrow.fp-prev' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}  .fp-controlArrow.fp-next' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'arrows_border_color',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					' {{WRAPPER}} .fp-controlArrow.fp-prev' => 'border-color: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}  .fp-controlArrow.fp-next' => 'border-color: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'arrows_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'.fp-controlArrow.fp-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.fp-controlArrow.fp-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
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
					'.fp-controlArrow.fp-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.fp-controlArrow.fp-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
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
					'.fp-controlArrow.fp-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'.fp-controlArrow.fp-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'navigation_horizontal_arrows' => 'yes',
				],
			]
		);
		

		$this->end_controls_section();
	}
	
	public function get_edit_buttom() {
		
	$settings = $this->get_settings();
		
	$template_id = $this->get_settings( 'template_id' );
	
	}
	
	protected function render() {
		$settings = $this->get_settings();	

			if ( empty( $settings['slides'] ) ) {
				return;
			}

			$this->add_render_attribute( 'button', 'class', [ 'bew-button', 'bew-slide-button' ] );

			if ( ! empty( $settings['button_size'] ) ) {
				$this->add_render_attribute( 'button', 'class', 'bew-size-' . $settings['button_size'] );
			}
			
			$slides = [];
			$anchors = [];
			$tooltips = [];
			$slide_count = 0;
			$fp_slide_count = 0;
			$section_id = 1;
			
		foreach ( $settings['slides'] as $slide ) {
				
				//Height
				$auto_height					= $slide['auto_height'];
				$auto_height_responsive			= $slide['auto_height_responsive'];	
								
				$wrap_classes_section = array( '' );	
				
				if('yes' == $auto_height){
					$wrap_classes_section[] = 'fp-auto-height';
				}
				
				if('yes' == $auto_height_responsive){
					$wrap_classes_section[] = 'fp-auto-height-responsive';
				}
											
				$wrap_classes_section = implode( ' ', $wrap_classes_section );
				
								
				//Templates
				
				$template = $slide['custom_template'];
				
								
					$slide_html = $slide_attributes = $btn_attributes = '';
					$btn_element = $slide_element = 'div';
					$slide_url = $slide['link']['url'];

					if ( ! empty( $slide_url ) ) {
						$this->add_render_attribute( 'slide_link' . $slide_count , 'href', $slide_url );

						if ( $slide['link']['is_external'] ) {
							$this->add_render_attribute( 'slide_link' . $slide_count, 'target', '_blank' );
						}

						if ( 'button' === $slide['link_click'] ) {
							$btn_element = 'a';
							$btn_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
						} else {
							$slide_element = 'a';
							$slide_attributes = $this->get_render_attribute_string( 'slide_link' . $slide_count );
						}
					}
					
					if ( 'yes' === $slide['background_overlay'] ) {
						$slide_html .= '<div class="bew-background-overlay"></div>';
					}
					
					
				if ('yes' == $template){

					$template_id = $slide['template_id'];
					$slide_html .= '<div class="bew-slide-content-template">';
					$slide_html .=	Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id );
				
				} else {
					
					$slide_html .= '<div class="bew-slide-content">';
							
							if ( $slide['subheading'] && $settings['subheading_position'] == 'before' ) {
								$slide_html .= '<div class="bew-slide-subheading">' . $slide['subheading'] . '</div>';
							}
							
							if ( $slide['heading'] ) {
								$slide_html .= '<div class="bew-slide-heading">' . $slide['heading'] . '</div>';
							}
							
							if ( $slide['subheading'] && $settings['subheading_position'] == 'after' ) {
								$slide_html .= '<div class="bew-slide-subheading">' . $slide['subheading'] . '</div>';
							}

							if ( $slide['description'] ) {
								$slide_html .= '<div class="bew-slide-description">' . $slide['description'] . '</div>';
							}

							if ( $slide['button_text'] ) {
								$slide_html .= '<' . $btn_element . ' ' . $btn_attributes . ' ' . $this->get_render_attribute_string( 'button' ) . '>' . $slide['button_text'] . '</' . $btn_element . '>';
							}
					
				}
					
					$ken_class = '';

					if ( '' != $slide['background_ken_burns'] ) {
						$ken_class = ' bew-ken-' . $slide['zoom_direction'];
					}
					
					if ( 'fade' === $settings['transition'] ) {	
						$fullpage_section_fade = 'fp-section-fade';
					} else {
						$fullpage_section_fade = '';
					}
					
					if ( 'parallax' === $settings['transition'] ) {	
						$fullpage_section_parallax = 'fp-bg';
					} else {
						$fullpage_section_parallax = '';
					}

					$slide_html .= '</div>';

				
				//Sections 
						
				if ('yes' == $slide['h_slider']){					
					if (0 == $fp_slide_count || 'yes' ==  $slide['h_slider_parent']){
						
						if ('yes' ==  $slide['h_slider_parent'] && 1 != $section_id){
							$fp_slide_count = 0; 	
							$slides[] = '</div>';	
							
							$slides[] = '<div id= "'.$section_id.'" class="section has-slides">';
						}else{
							$slides[] = '<div id= "'.$section_id.'" class="section has-slides hide-nav">';	
						}
							
							$anchors[] = '"' .sanitize_title($slide['section_name']) . '", ';
							$tooltips[] = '"' .strip_tags($slide['section_name']) . '", ';
							$hidenavs[] = $section_id;
							$section_id++;
					}
							// slide html 						
							if ('yes' == $template){
							$slide_html = '<div class="fullpage-slide-bg ' .$fullpage_section_parallax . $ken_class . '"></div><' . $slide_element . ' ' . $slide_attributes . ' class="fullpage-slide-inner-template">' . $slide_html . '</' . $slide_element . '>';
							} else {
							$slide_html = '<div class="fullpage-slide-bg ' .$fullpage_section_parallax . $ken_class . '"></div><' . $slide_element . ' ' . $slide_attributes . ' class="fullpage-slide-inner">' . $slide_html . '</' . $slide_element . '>';
							
							}								
							// slide section
							
							$slides[] = '<div id="slide-' .$fp_slide_count .'" class="slide ' .esc_attr( $wrap_classes_section ) .' elementor-repeater-item-' . $slide['_id'] . ' fullpage-slide ' . $fullpage_section_fade . '">' . $slide_html .'</div>';
							
							$slide_count++;	
							$fp_slide_count++;
							$fp_slide_type = 'last';
													
				}else {
							if ('last' == $fp_slide_type){
							$fp_slide_count = 0;
							}
							
							if ( 1 != $section_id){
							$slides[] = '</div>';
							}

							// slide html 							
							if ('yes' == $template){
								if ( 'parallax' === $settings['transition'] ) {		
								$slide_html = '<div class="fullpage-slide-bg ' .$fullpage_section_parallax . $ken_class . '"></div><' . $slide_element . ' ' . $slide_attributes . ' class="fullpage-slide-inner-template">' . $slide_html . '</' . $slide_element . '>';
								}else{
								$slide_html = $slide_html;	
								}
							} else {
							$slide_html = '<div class="fullpage-slide-bg ' .$fullpage_section_parallax . $ken_class . '"></div><' . $slide_element . ' ' . $slide_attributes . ' class="fullpage-slide-inner">' . $slide_html . '</' . $slide_element . '>';
								
							}					
							// slide section
							$slides[] = '<div id= "'.$section_id.'" class="section ' . esc_attr( $wrap_classes_section ) .' elementor-repeater-item-' . $slide['_id'] . ' fullpage-slide ' . $fullpage_section_fade . '">' . $slide_html;
							
							$anchors[] = '"' .sanitize_title($slide['section_name']) . '", ';
							$tooltips[] = '"' .strip_tags($slide['section_name']) . '", ';
							
							$slide_count++;	
							$section_id++;
							$fp_slide_type = '';
				}
		}
		
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$show_dots = ( in_array( $settings['navigation_dots'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation_dots'], [ 'arrows', 'both' ] ) );

		//Fullpage options
			
			//Navigation settings
			$navigation = $settings['navigation_dots'];
			$position   = $settings['navigation_dots_position'];
			$navigation_horizontal = $settings['navigation_horizontal'];
			$navigation_horizontal_arrows = $settings['navigation_horizontal_arrows'];			
			
			//Scrolling settings
			$scrollingspeed = $settings['scrolling_speed'];			
			
			//Anchors			
			if ( $settings['navigation_anchors'] == 'yes') {
			$anchors = '[' . implode($anchors) . ']';			
			}else{
			$anchors = '[]';		
			}
			
			//Tooltips
			$tooltips = '[' . implode($tooltips) . ']';				
			
			//HideNav
			if ( $settings['slide_type'] == 'vertical' && !empty($hidenavs) ) {
				$hidenavs_count = 1;
				
				foreach ( $hidenavs as $key=>$hidenav) {
				if ($hidenavs_count != 1) {
				$hide_nav_list .= '||';
				}
				$hidenav = $hidenav - 1;
				$hide_nav_list .= 'destination.index === ' .$hidenav; 
				$hidenavs_count++;
				}
			} else {
				$hide_nav_list = "''";
			}
			
			//Backgrounds
			$background_count = 1;
			$backgrounds = '[';
			foreach ( $settings['slides'] as $slide ) {
			$backgrounds .= '"' .$slide['background_color'] . '", ';
					
			$background_count++;
			}
			$backgrounds .= ']';
						
			//ScrollOverflow settings
			$scrollOverflow = $settings['scrollOverflow'];
			
						
		if ( 'yes' === $settings['responsivesections'] ) {	
			
			$responsiveWidth = $settings['responsiveWidth'];			
			$responsiveHeight = $settings['responsiveHeight'];
		}else {
			
			$responsiveWidth = 0;			
			$responsiveHeight = 0;
		}	
		
		//Slide type
		
		$slide_type = $settings['slide_type'];
				
		if ( 'horizontal' === $settings['slide_type'] ) {	
		
		$navigation = '';
		}
		
		//Transition Slide settings
		
		if ( 'fade' === $settings['transition'] ) {
			$fullpage_fade = 'fullpage-wrapper-fade';			
		}else{
			$fullpage_fade = '';
		}

		$carousel_classes = [ 'fullpage-slides' ];

		$this->add_render_attribute( 'slides', [
			'class' => $carousel_classes,			
		] );

		//Transition Parallax settings
		
		if ( 'parallax' === $settings['transition'] ) {
			$parallax = 'yes';
			if(empty(get_option('fullpage_parallax_key'))){ 
			$parallax_key = '';
			}else{
			$parallax_key = get_option('fullpage_parallax_key');	
			}			
		}else{
			$parallax = '';
			$parallax_key = '';			
		}

		$carousel_classes = [ 'fullpage-slides' ];

		$this->add_render_attribute( 'slides', [
			'class' => $carousel_classes,			
		] );


		
		?>
		<div id="fullpage" class="elementor-wrapper <?php echo $fullpage_fade; ?> " dir="<?php echo $direction; ?>">
			<div <?php echo $this->get_render_attribute_string( 'slides' ); ?>>
				<?php echo implode( '', $slides ); ?>
			</div>
		</div>
		
		<?php
		
	?>
       <script type="text/javascript">
		( function( $ ) {
			$(document).ready(function() {				
			
			if( '<?php echo $settings['menu_show'] ?>' == 'yes' ){
						
					$('header').addClass('fullpage-transparent-header');					
			}
			
			// Keep playing video
			if( '<?php echo $settings['keep_playing_video'] ?>' == 'yes' ){
								
				$('.elementor-video-iframe').addClass('keepplaying');				
				$('.elementor-background-video-embed').addClass('keepplaying');
				$('.elementor-video').addClass('keepplaying');
				setTimeout(function() {
				$('.elementor-video-iframe').attr('data-keepplaying', '');					
				$('.elementor-background-video-embed').attr('data-keepplaying', '');
				$('.elementor-video').attr('data-keepplaying', '');
				}, 2000);			
				
			}
						
			var slide_type = '<?php echo $slide_type ?>',
				navigation_horizontal = '<?php echo $navigation_horizontal; ?>',
				navigation_horizontal_arrows = '<?php echo $navigation_horizontal_arrows ?>';			
			
			if($(window).width() < 1024  && 'horizontal' === '<?php echo $settings['slide_type'] ?>' && 'yes' === '<?php echo $settings['responsivesections']?>' ){ 
			
			var slide_type = 'vertical',
				navigation_horizontal = '',
				navigation_horizontal_arrows = '';	
			
			}
			
			//destroying
			if (typeof $.fn.fullpage.destroy == 'function') { 
			$.fn.fullpage.destroy('all');
			}
			
			//initializing 	
			$( '#fullpage' ).fullpage( {
				licenseKey: '',
				//Navigation
				menu: '#fp-nav',
				lockAnchors: false,
				anchors:<?php echo $anchors; ?>,
				navigation:  '<?php echo $navigation; ?>' == 'yes' ? true : false,
				navigationPosition: '<?php echo $position; ?>',
				navigationTooltips: <?php echo $tooltips; ?>,
				showActiveTooltip: false,
				slidesNavigation: navigation_horizontal == 'yes' ? true : false,
				slidesNavPosition: 'bottom',

				//Scrolling
				css3: true,
				scrollingSpeed: '<?php echo $scrollingspeed; ?>',
				autoScrolling: true,
				fitToSection: true,
				fitToSectionDelay: 1000,
				scrollBar: false,
				easing: 'easeInOutCubic',
				easingcss3: 'ease',
				loopBottom: false,
				loopTop: false,
				loopHorizontal: false,
				continuousVertical: false,
				continuousHorizontal: false,
				scrollHorizontally: slide_type == 'horizontal' ? true : false,
				interlockedSlides: false,
				dragAndMove: false,
				offsetSections: false,
				resetSliders: false,
				fadingEffect: false,
				normalScrollElements: '#element1, .element2',
				scrollOverflow: '<?php echo $scrollOverflow; ?>' == 'yes' ? true : false,
				scrollOverflowReset: false,
				scrollOverflowOptions: { // option for the iscroll lib
										"mouseWheelSpeed": 5,
				},
				touchSensitivity: 15,
				normalScrollElementTouchThreshold: 5,
				bigSectionsDestination: null,

				//Accessibility
				keyboardScrolling: true,
				animateAnchor: true,
				recordHistory: true,

				//Design
				controlArrows: navigation_horizontal_arrows == 'yes' ? true : false,
				verticalCentered: true,
				sectionsColor : <?php echo $backgrounds; ?>,
				paddingTop: '0px',
				paddingBottom: '0px',
				fixedElements: '#header, .footer',
				responsiveWidth: <?php echo $responsiveWidth; ?>,
				responsiveHeight: <?php echo $responsiveHeight; ?>,
				responsiveSlides: false,
				parallaxKey: '<?php echo $parallax_key; ?>',
				parallax: '<?php echo $parallax; ?>' == 'yes' ? true : false,				
				parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},

				//Custom selectors				
				sectionSelector: slide_type == 'horizontal' ? 'div.fullpage-slides' : '.section',
				slideSelector:  slide_type == 'horizontal' ? '.section' : '.slide',

				lazyLoading: true,

				//events
				onLeave: function(origin, destination, direction){
					if( '<?php echo $settings['menu_show'] ?>' == 'yes' ){
					
					
					var leavingSection = $(this);
					//hide logo in scroll down
						if(direction =='down'){
							$('header').addClass('hide-logo');   
						}
						else if(direction == 'up'){
							$('header').removeClass('hide-logo');  
						}				
					}
					if( slide_type == 'vertical' && "<?php echo $hide_nav_list?>" != null ){
					// hide dots on slider					
														
					if (<?php echo $hide_nav_list?>) {
						$('#fp-nav').hide(); // or toggle by class
					}
					else {
						$('#fp-nav').show(); // or toggle by class
					}
					}
					
					
					//Onleave reset elementor animations if go up
					if( '<?php echo $settings['reset_animation'] ?>' == 'yes' ){
					var Id = parseInt($('.section.active').attr('id'));					
					var animated = $('#' + Id + ' .animated');
					
					animated.each(function() {
						
						var that = $(this);
						var data_animation = $(this).data('settings');
						if(data_animation ){
						var animation = data_animation["animation"];
						var animation_widget = data_animation["_animation"];
						
						$(this).removeClass(animation + ' ' + animation_widget );
						setTimeout(function(){ 
							that.addClass('elementor-invisible'); 							
						}, 600 );
						
						}
					
					});
					}
					
					//Custom Onleave Callback 
					
					<?php echo $settings['callback_onleave'] ?>
					
				},
				afterLoad: function(origin, destination, direction){

					var loadedSection = $(this);
				
				//afterload elementor animations
					
					var Id = parseInt($('.section.active').attr('id'));						
					var animated = $('#' + Id + ' .elementor-invisible');
					
					
					animated.each(function() {
					
						var that = $(this);
						var data_animation = $(this).data('settings');
						
						if(data_animation ){
						var animation = data_animation["animation"];
						var animation_widget = data_animation["_animation"];
						var animationDelay = data_animation["animation_delay"];
						
						
						//elementor animations
						setTimeout(function(){						
						that.removeClass('elementor-invisible ' + animation + ' ' + animation_widget).addClass('animated ' + animation + ' ' + animation_widget);												
						}, animationDelay );					
						}
					
					});
				
				//hide dots if id=1 is slider
			
				if($( '#1' ).hasClass( "hide-nav" ) && origin.index ==1 ){
				 $('#fp-nav').hide();	
				}
				
				// Keep playing video
				if( '<?php echo $settings['keep_playing_video'] ?>' == 'yes' ){
									
					$('.elementor-video-iframe').attr('data-keepplaying', '');					
					$('.elementor-background-video-embed').attr('data-keepplaying', '');
					$('.elementor-video').attr('data-keepplaying', '');
				}
				
				//Custom afterLoad Callback 
					
				<?php echo $settings['callback_afterLoad'] ?>
							
					
				},
				afterRender: function(){},
				afterResize: function(){},
				afterResponsive: function(isResponsive){},
				afterSlideLoad: function(anchorLink, index, slideAnchor, slideIndex){
					
					
					
				},
				onSlideLeave: function(anchorLink, index, slideIndex, direction, nextSlideIndex){}
				
				});
				
				//Scroll Horizontally script
					if( slide_type == 'horizontal' ){
					$('.fp-section').bind('mousewheel DOMMouseScroll', function(e){
						if(e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0){
							$.fn.fullpage.moveSlideLeft();   
						}else{
							 $.fn.fullpage.moveSlideRight();
						}
						return false;
					});
					}
				
				// The changes that were made

				
					
				});
		} )( jQuery );		
	</script>
		<?php
	
	}
	
}


Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_fullpage() );