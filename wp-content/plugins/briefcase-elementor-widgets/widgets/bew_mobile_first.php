<?php
namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BEW_Widget_mobile_first extends Widget_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'bewmobilefirst';
	}
	
	public function get_script_depends() {
		return [ 'woo-mobile-first'];
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_bew_mobile_first',
			[
				'label' => __( 'Bew Mobile First Design', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_ADVANCED,				
			]
		);

		$element->add_control(
			'bew_mobile_first',
			[
				'label' => __( 'Enable', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' => __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' => 'yes',
				'default' => '',
				'frontend_available' => true,
				'prefix_class'  => 'bew-mobile-first-'
			]
		);

		$element->end_controls_section();
	}
	
	private function add_actions() {
				
		add_action( 'elementor/element/column/section_custom_css/before_section_start', [ $this, 'register_controls' ] );	
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );		
		add_action( 'elementor/frontend/column/after_render', [ $this, 'after_render_bew_mobile_first'], 10, 1 );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'after_render_bew_mobile_first'], 10, 1 );
		
	}
	
	public function after_render_bew_mobile_first($element) {
		$settings 		= $element->get_settings();
		if (isset($settings['bew_mobile_first'])){
		$bew_mobile_first    = $settings['bew_mobile_first'];
		
		if ($bew_mobile_first) {
		$id = $element->get_id();
		$selector = '".elementor-element-' . $id . '"';		 
		?>
		<script type="text/javascript">
			jQuery(function($) {				
			$(<?php echo $selector; ?>).addClass('bmfd__fold fold'); 
			$(<?php echo $selector; ?> + " .elementor-element.elementor-widget-heading:first-of-type").addClass('heading--add fold__toggle');
			$(<?php echo $selector; ?> + " .elementor-element").not(".elementor-widget-heading:first-of-type").not(".elementor-column").addClass('fold__content'); 
			});		
		</script>	
		<?php
		}
		}
					
	}
			
}

Plugin::instance()->widgets_manager->register_widget_type( new BEW_Widget_mobile_first() );