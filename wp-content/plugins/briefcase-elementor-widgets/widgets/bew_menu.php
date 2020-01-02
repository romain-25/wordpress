<?php
/**
 * Bew Fullpage Module
 */

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEW_Widget_menu extends Widget_Base {

	public function get_name() {
		return 'bew-menu';
	}

	public function get_title() {
		return __( 'BEW Menu', 'briefcase-elementor-widgets' );
	}

	public function get_icon() {
		// Upload "eicons.ttf" font via this site: http://bluejamesbond.github.io/CharacterMap/
		return 'eicon-thumbnails-right';
	}

	public function get_categories() {
		return [ 'briefcasewp-elements' ];

	}
		
	public function get_script_depends() {
		return [ 'global' ];
	}
	
	

	protected function _register_controls() {
		$this->start_controls_section(
			'section_menu',
			[
				'label' => __( 'List', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'menus_repeater' );

		$repeater->start_controls_tab( 'Menu', [ 'label' => __( 'Menu', 'briefcase-elementor-widgets' ) ] );
		
		$repeater->add_control(
			'menu_list',
			[
				
				'label' => __( 'Item', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);
		
		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'http://your-page-link.com', 'briefcase-elementor-widgets' ),
			]
		);
		
		$repeater->end_controls_tab();
		
		$repeater->start_controls_tab( 'Image', [ 'label' => __( 'Image', 'briefcase-elementor-widgets' ) ] );
		
		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [],				
			]
		);
		
		$repeater->add_control(
			'heading',
			[
				'label' => __( 'Title & Description', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Slide Heading', 'briefcase-elementor-widgets' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'briefcase-elementor-widgets' ),
				'show_label' => false,
			]
		);
		
		$repeater->end_controls_tab();
		
		$this->add_control(
			'nav_list',
			[
				'label' => __( 'Items', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => true,
				'default' => [
					[
						'menu_list' => __( 'Item 1 name', 'briefcase-elementor-widgets' ),
						'link' => [ 'url' => '#' ],
											
					],
					[
						'menu_list' => __( 'Item 2 name', 'briefcase-elementor-widgets' ),
						'link' => [ 'url' => '#' ],						
					],
					[
						'menu_list' => __( 'Item 3 name', 'briefcase-elementor-widgets' ),
						'link' => [ 'url' => '#' ],					
					],
				],
				'fields' => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ menu_list }}}',
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
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 610,
				],
				'size_units' => [ 'px', 'vh', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .main-content-inner-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();		
		

		$this->start_controls_section(
			'section_menu_style',
			[
				'label' => __( 'Menu Style', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'heading_menu_item',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Menu Item', 'briefcase-elementor-widgets' ),
			]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'color_menu_item',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bew-navigation .menu-list .bew-menu-list-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'color_menu_item_hover',
			[
				'label' => __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .bew-navigation .menu-list .bew-menu-list-item:hover,
					{{WRAPPER}} .bew-navigation .menu-list .active .bew-menu-list-item' => 'color: {{VALUE}}',
				],		
			]
		);
				
		$this->end_controls_tab();
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'menu_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),				
				'selector' => '{{WRAPPER}} .bew-navigation',
			]
		);
		
		$this->add_responsive_control(
			'menu_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => __( 'Image Style', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		$this->add_control(
			'Image_title',
			[
				'label' => __( 'Image', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'label' => __( 'Image Size', 'briefcase-elementor-widgets' ),
				'default' => 'thumbnail',
			]
		);
		
		$this->add_control(
			'image_fixed',
			[
				'label' 		=> __( 'Image Fixed', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'no',
				'label_on' 		=> __( 'Yes', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'No', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',			
			]
		);
		
		$this->add_responsive_control(
			'image_padding',
			[
				'label' => __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .collection-images .image-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'image_margin',
			[
				'label' => __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .collection-images .image-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-image-header' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bew-image-header',
			]
		);
		
		$this->add_control(
			'heading_item_description',
			[
				'label' => __( 'Description', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-image-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => __( 'Typography', 'briefcase-elementor-widgets' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .bew-image-description',
			]
		);

		$this->end_controls_section();

	}

	private function render_image( $item2, $settings ) {
		$image_id = $item2['image']['id'];
		$image_size = $settings['image_size_size'];
		if ( 'custom' === $image_size ) {
			$image_src = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $settings );
		} else {
			$image_src = wp_get_attachment_image_src( $image_id, $image_size );
			$image_src = $image_src[0];
		}

		return sprintf( '<img src="%s" alt="%s" />', $image_src, $item2['menu_list'] );
	}

	private function render_item_header( $item ) {
		$url = $item['link']['url'];

		$item_id = $item['_id'];

		if ( $url ) {
			$unique_link_id = 'item-link-' . $item_id;

			$this->add_render_attribute( $unique_link_id, [
				'href' => $url,
				'class' => 'bew-menu-list-item',
			] );

			if ( $item['link']['is_external'] ) {
				$this->add_render_attribute( $unique_link_id, 'target', '_blank' );
			}

			return '<a ' . $this->get_render_attribute_string( $unique_link_id ) . '>';
		} else {
			return '<li class="bew-menu-list-item">';
		}
	}

	private function render_item_footer( $item ) {
		if ( $item['link']['url'] ) {
			return '</a>';
		} else {
			return '</li>';
		}
	}

	protected function render() {
		$settings = $this->get_settings();
		
		// vars
		$fixed = $settings['image_fixed'];
		
		if ( 'yes' == $fixed) {
				$class_fixed = 'fixed';
			}
		
		?>		
	<div class="main-content-inner-wrapper">
		<?php
		// Menu
		?>
		<nav class="bew-navigation collection-nav" role="navigation" id ="bew-nav" >
		<ul id="menu-list" class="menu-list " >		
		
		<?php
		$item_count = 0;
		foreach ( $settings['nav_list'] as $item ) {			 
		?>			
				<li class="collection-nav-item" id="item-menu-list-<?php echo $item_count ?>">
		<?php	echo $this->render_item_header( $item ); ?>
				<span class="collection-nav-item-span"><?php echo $item['menu_list'] ?></span>
		<?php	echo $this->render_item_footer( $item ); ?>
				</li><!--end .product-buttons-->

		<?php
		$item_count++;
		} ?>
		</ul>
		</nav>
		
		<?php
		// Image
		?>
		
		<div class="collection-images <?php echo $class_fixed?>">
		<div class="image-container index-main-image no-main-image-bg"></div>		
		
		<?php
		$item_count = 0;
		foreach ( $settings['nav_list'] as $item2 ) {			 
		?>
		
		
		<?php	if ( ! empty( $item2['image']['url'] ) ) { ?>
		<div class="image-container item-menu-list-<?php echo $item_count ?>" id="item-image-<?php echo $item_count ?>"> <?php echo $this->render_image( $item2, $settings )?> </div>
		<?php	
		} ?>
		
		
		<?php
		$item_count++;
		} ?>
		<div class="overlay blended-overlay index-overlay"></div>
		</div>
		
	</div>
	
			<script type="text/javascript">
			jQuery(function($) {

				$('#bew-nav li').hover(function(e) {
					$('.' + this.id)[e.type == 'mouseenter' ? 'addClass' : 'removeClass']('active');      
				})
					
				$('#bew-nav li').first().addClass('active');
				$('#item-image-0').addClass('active'); 
				
				$('li',this).on('mouseenter mouseleave', function(){
					$('#bew-nav li').first().removeClass('active');
					$(this).toggleClass('active');
					
				});
				
				$('body,#bew-nav').on('mouseleave', function(){	
						$('#bew-nav li').first().addClass('active'); 
						$('#item-image-0').addClass('active'); 
				 });
				 
				 // Menu dropdown horizontal.
	
				
				$('#menu-dc').click(function () {
				if ( $( '#menu-dc div' ).hasClass( "elementor-active" ) ) {
  
				$('#logo-dc').addClass('hide-logo');    
				} else {
				$('#logo-dc').removeClass('hide-logo'); 	
				}
				});				
				
			});		
			</script>
<?php
		} 
}	


Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_menu() );