<?php
/**
 * Adds custom metabox
 *
 * @package Briefcase Elementor Widgets
 * @category Core
 * @author BriefcaseWp
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The Metabox class
if ( ! class_exists( 'BriefcaseWP_Post_Metabox' ) ) {

	/**
	 * Main ButterBean class.  Runs the show.
	 *
	 * @since  1.1.2
	 * @access public
	 */
	final class BriefcaseWP_Post_Metabox {

		private $post_types;
		private $default_control;
		private $custom_control;

		/**
		 * Register this class with the WordPress API
		 *
		 * @since 1.1.2
		 */
		private function setup_actions() {

			// Capabilities
			$capabilities = apply_filters( 'briefcase_main_metaboxes_capabilities', 'manage_options' );

			// Post types to add the metabox to
			$this->post_types = apply_filters( 'briefcase_main_metaboxes_post_types', array(
				'product',
				'elementor_library',				
			) );

			// Default butterbean controls
			$this->default_control = array(
				'select',
				'color',
				'image',
				'text',
				'number',
				'textarea',
			);
			
			// Custom butterbean controls
			$this->custom_control = array(
				'buttonset' 		=> 'BriefcaseWP_ButterBean_Control_Buttonset',				
			);
			
			// Overwrite default controls
			add_filter( 'butterbean_pre_control_template', array( $this, 'default_control_templates' ), 10, 2 );
			
			// Register custom controls
			add_filter( 'butterbean_control_template', array( $this, 'custom_control_templates' ), 10, 2 );
			
			// Register new controls types
			add_action( 'butterbean_register', array( $this, 'register_control_types' ), 10, 2 );

			
			if ( current_user_can( $capabilities ) ) {

				// Register fields
				add_action( 'butterbean_register', array( $this, 'register' ), 10, 2 );
				
				// Register fields for the posts
				add_action( 'butterbean_register', array( $this, 'product_register' ), 10, 2 );
				
				// Register scripts and styles.
				add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

				// Load scripts and styles.
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			}
			
			// Custom CSS
			add_filter( 'briefcase_head_css', array( $this, 'head_css' ) );

		}

		/**
		 * Load butterbean scripts and styles
		 *
		 * @since 1.1.0
		 */
		public function register_scripts() {
			$min = ( SCRIPT_DEBUG ) ? '' : '.min';

			// Default style
			wp_register_style( 'briefcasewp-butterbean', plugins_url( '/controls/assets/css/butterbean'. $min .'.css', __FILE__ ) );

			// Default script.
			wp_register_script( 'briefcasewp-butterbean', plugins_url( '/controls/assets/js/butterbean'. $min .'.js', __FILE__ ), array( 'butterbean' ), '', true );
			
			// Enqueue the select2 script, I use "oceanwp-select2" to avoid plugins conflicts
			wp_register_script( 'briefcasewp-select2', plugins_url( '/controls/assets/js/select2.full.min.js', __FILE__ ), array( 'jquery' ), false, true );

			// Enqueue the select2 style
			wp_register_style( 'select2', plugins_url( '/controls/assets/css/select2.min.css', __FILE__ ) );

		}

		/**
		 * Load scripts and styles
		 *
		 * @since 1.1.0
		 */
		public function enqueue_scripts( $hook ) {

			// Only needed on these admin screens
			if ( $hook != 'edit.php' && $hook != 'post.php' && $hook != 'post-new.php' ) {
				return;
			}

			// Get global post
			global $post;

			// Return if post is not object
			if ( ! is_object( $post ) ) {
				return;
			}

			// Return if wrong post type
			if ( ! in_array( $post->post_type, $this->post_types ) ) {
				return;
			}

			// Enqueue scripts
			wp_enqueue_script( 'briefcasewp-metabox-script', plugins_url( '/assets/js/metabox.js', __FILE__ ), array( 'jquery' ), BEW_VERSION, true );
			wp_enqueue_style( 'briefcasewp-butterbean' );
			wp_enqueue_script( 'briefcasewp-butterbean' );
			wp_enqueue_script( 'briefcasewp-select2' );
			wp_enqueue_style( 'select2' );

		}
		
		/**
		 * Registers control types
		 *
		 * @since  1.2.4
		 */
		public function register_control_types( $butterbean ) {
			$controls = $this->custom_control;

			foreach ( $controls as $control => $class ) {

				require_once( BEW_PATH . '/includes/metabox/controls/'. $control .'/class-control-'. $control .'.php' );
				$butterbean->register_control_type( $control, $class );

			}
		}
		
		/**
		 * Get default control templates
		 *
		 * @since  1.1.0
		 */
		public function default_control_templates( $located, $slug ) {
			$controls = $this->default_control;

			foreach ( $controls as $control ) {

				if ( $slug === $control ) {
					return BEW_PATH . '/includes/metabox/controls/'. $control .'/template.php';
				}

			}

			return $located;
		}
		
		/**
		 * Get custom control templates
		 *
		 * @since  1.1.0
		 */
		public function custom_control_templates( $located, $slug ) {
			$controls = $this->custom_control;

			foreach ( $controls as $control => $class ) {

				if ( $slug === $control ) {
					return BEW_PATH . '/includes/metabox/controls/'. $control .'/template.php';
				}

			}

			return $located;
		}

		
		/**
		 * Registration callback
		 *
		 * @since 1.1.2
		 */
		public function register( $butterbean, $post_type ) {

			// Post types to add the metabox to
			$post_types = $this->post_types;
			$brand = 'BriefcaseWp';
			
			
			// Register managers, sections, controls, and settings here.
			$butterbean->register_manager(
		        'briefcasewp_mb_settings',
		        array(
		            'label'     => $brand . ' ' . esc_html__( 'Settings', 'briefcase-elementor-widgets' ),
		            'post_type' => $post_types,
		            'context'   => 'normal',
		            'priority'  => 'high'
		        )
		    );
			
			// Return if it is not Post post type
			if ( 'product' == $post_type ) {
				return;
			}
			
			$manager = $butterbean->get_manager( 'briefcasewp_mb_settings' );
			
			$manager->register_section(
		        'briefcasewp_mb_template',
		        array(
		            'label' => esc_html__( 'Template', 'briefcase-elementor-widgets' ),
		            'icon'  => 'dashicons-admin-generic'
		        )
		    );

		    $manager->register_control(
		        'briefcase_template_layout', // Same as setting name.
		        array(
		            'section' 		=> 'briefcasewp_mb_template',
		            'type'    		=> 'select',
		            'label'   		=> esc_html__( 'Template Type', 'briefcase-elementor-widgets' ),
		            'description'   => esc_html__( 'Select template type', 'briefcase-elementor-widgets' ),
					'choices' 		=> array(
						'' 				=> esc_html__( 'Default', 'briefcase-elementor-widgets' ),
						'woo-product' => esc_html__( 'Woocommerce Single Product', 'briefcase-elementor-widgets' ),
						'woo-shop' 	=> esc_html__( 'Woocommerce Shop', 'briefcase-elementor-widgets' ),
						'woo-cat' 	=> esc_html__( 'Woocommerce Category', 'briefcase-elementor-widgets' ),
						'edd-product' 	=> esc_html__( 'Edd Single Product', 'briefcase-elementor-widgets' ),
						'edd-shop' 	=> esc_html__( 'Edd Shop', 'briefcase-elementor-widgets' ),
						
					),
		        )
		    );
			
			$manager->register_setting(
		        'briefcase_template_layout', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		        )
		    );
			
			$manager->register_control(
		        'briefcase_template_layout_shop', // Same as setting name.
		        array(
		            'section' 		=> 'briefcasewp_mb_template',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Woocommerce Shop', 'briefcase-elementor-widgets' ),
		            'description'   => esc_html__( 'Apply this template on the default Woocommerce shop page.', 'briefcase-elementor-widgets' ),
					'choices' 		=> array(						
						'on' 		=> esc_html__( 'Enable', 'briefcase-elementor-widgets' ),
						'off' 		=> esc_html__( 'Disable', 'briefcase-elementor-widgets' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'briefcase_template_layout_shop', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );
			
			$manager->register_control(
		        'briefcase_template_layout_cat', // Same as setting name.
		        array(
		            'section' 		=> 'briefcasewp_mb_template',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Woocommerce Categories', 'briefcase-elementor-widgets' ),
		            'description'   => esc_html__( 'Apply this template on the default Woocommerce categories page.', 'briefcase-elementor-widgets' ),
					'choices' 		=> array(						
						'on' 		=> esc_html__( 'Enable', 'briefcase-elementor-widgets' ),
						'off' 		=> esc_html__( 'Disable', 'briefcase-elementor-widgets' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'briefcase_template_layout_cat', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );
			
		}
		
		/**
		 * Registration callback
		 *
		 * @since 1.1.0
		 */
		public function product_register( $butterbean, $post_type ) {

			// Return if it is not Post post type
			if ( 'product' != $post_type ) {
				return;
			}

		// Gets the manager object we want to add sections to.
		$manager = $butterbean->get_manager( 'briefcasewp_mb_settings' );
			
		$manager->register_section(
		        'briefcasewp_mb_global',
		        array(
		            'label' => esc_html__( 'Global', 'briefcase-elementor-widgets' ),
		            'icon'  => 'dashicons-sticky'
		        )
		    );
			
			$manager->register_control(
		        'briefcase_apply_global', // Same as setting name.
		        array(
		            'section' 		=> 'briefcasewp_mb_global',
		            'type'    		=> 'buttonset',
		            'label'   		=> esc_html__( 'Global Template', 'briefcase-elementor-widgets' ),
		            'description'   => esc_html__( 'Apply global product template.', 'briefcase-elementor-widgets' ),
					'choices' 		=> array(						
						'on' 		=> esc_html__( 'Enable', 'briefcase-elementor-widgets' ),
						'off' 		=> esc_html__( 'Disable', 'briefcase-elementor-widgets' ),
					),
		        )
		    );
			
			$manager->register_setting(
		        'briefcase_apply_global', // Same as control name.
		        array(
		            'sanitize_callback' => 'sanitize_key',
		            'default' 			=> 'on',
		        )
		    );
			
		}

		
		/**
		 * Sanitize function for integers
		 *
		 * @since  1.0.0
		 */
		public function sanitize_absint( $value ) {
			return $value && is_numeric( $value ) ? absint( $value ) : '';
		}

		
		/**
		 * Returns the instance.
		 *
		 * @since  1.1.2
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			static $instance = null;
			if ( is_null( $instance ) ) {
				$instance = new self;
				$instance->setup_actions();
			}
			return $instance;
		}

		/**
		 * Constructor method.
		 *
		 * @since  1.1.2
		 * @access private
		 * @return void
		 */
		private function __construct() {}

	}

	BriefcaseWP_Post_Metabox::get_instance();

}