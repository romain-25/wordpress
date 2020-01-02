<?php
	namespace Elementor;

	// Elementor Classes
	use Elementor\Widget_Base;
	use Elementor\Controls_Manager;
	use Elementor\Repeater;
	use Elementor\Group_Control_Border;
	use Elementor\Group_Control_Typography;
	use Elementor\Scheme_Typography;
	use MasterAddons\Inc\Helper\Master_Addons_Helper;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 10/27/19
	 */

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) { exit; }

	/**
	 * Master Addons: Pricing Table
	 */
	class Master_Addons_Pricing_Table extends Widget_Base {

		public function get_name() {
			return 'ma-pricing-table';
		}

		public function get_title() {
			return __( 'MA Pricing Table', MELA_TD );
		}

		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_icon() {
			return 'ma-el-icon eicon-price-table';
		}

		public function get_keywords() {
			return [ 'pricing', 'pricing table', 'pricingtable', 'comparision table'];
		}

		protected function _register_controls() {

			$this->start_controls_section(
				'ma_el_pricing_table_section_start',
				[
					'label' => __( 'Pricing Contents', MELA_TD ),
				]
			);



			$this->end_controls_tab();
		}


		protected function render() {
			echo "Pricing Table";
		}
	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Pricing_Table());
