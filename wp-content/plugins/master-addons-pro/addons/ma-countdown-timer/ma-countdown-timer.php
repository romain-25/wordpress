<?php
	namespace Elementor;
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/27/19
	 */
	use Elementor\Widget_Base;

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


	class Master_Addons_Countdown_Timer extends Widget_Base {

		//use ElementsCommonFunctions;
		public function get_name() {
			return 'ma-el-countdown-timer';
		}
		public function get_title() {
			return esc_html__( 'MA Countdown Timer', MELA_TD );
		}
		public function get_icon() {
			return 'ma-el-icon eicon-countdown';
		}
		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_script_depends() {
			return [ 'master-addons-countdown' ];
		}

		protected function _register_controls() {

			/**
			 * Master Addons: Countdown Timer Settings
			 */
			$this->start_controls_section(
				'ma_el_section_countdown_settings_general',
				[
					'label' => esc_html__( 'Timer Settings', MELA_TD )
				]
			);

			$this->add_control(
				'ma_el_countdown_time',
				[
					'label' => esc_html__( 'Countdown Date', MELA_TD ),
					'type' => Controls_Manager::DATE_TIME,
					'default' => date("Y/m/d", strtotime("+ 1 week")),
					'description' => esc_html__( 'Set the date and time here', MELA_TD ),
				]
			);

			$this->end_controls_section();


			/*
			 * Countdown Timer Styling Section
			 */

			$this->start_controls_section(
				'ma_el_section_countdown_styles_preset',
				[
					'label' => esc_html__( 'General Styles', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_control(
				'ma_el_countdown_preset',
				[
					'label' => esc_html__( 'Style Preset', MELA_TD ),
					'type' => Controls_Manager::SELECT,
					'default' => 'style-1',
					'options' => [
						'style-1' 	=> esc_html__( 'Style 1', MELA_TD ),
						'style-2' 	=> esc_html__( 'Style 2', MELA_TD ),
					],
				]
			);

			$this->add_control(
				'ma_el_countdown_divider_color',
				[
					'label' => __( 'Divider Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#ffffff',
					'selectors' => [
						'{{WRAPPER}} .ma-el-countdown.style-2 .ma-el-countdown-count::after' => 'color: {{VALUE}};',
					],
					'condition' => [
						'ma_el_countdown_preset' => 'style-2',
					],
				]
			);

			$this->add_control(
				'ma_el_countdown_container_bg_color',
				[
					'label' => __( 'Background Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#8868fe',
					'selectors' => [
						'{{WRAPPER}} .ma-el-countdown.style-2' => 'background: {{VALUE}};',
					],
					'condition' => [
						'ma_el_countdown_preset' => 'style-2',
					],
				]
			);

			$this->end_controls_section();


			$this->start_controls_section(
				'ma_el_section_countdown_box_style',
				[
					'label' => esc_html__( 'Box Style', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'ma_el_countdown_preset' => 'style-1',
					],
				]
			);



			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'ma_el_countdown_background',
					'label' => __( 'Background', MELA_TD ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .ma-el-countdown.style-1 .ma-el-countdown-container',
					'condition' => [
						'ma_el_countdown_preset' => 'style-1',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'label' => __( 'Box Shadow', MELA_TD ),
					'selector' => '{{WRAPPER}} .ma-el-countdown-container',
					'condition' => [
						'ma_el_countdown_preset' => 'style-1',
					],
				]
			);

			$this->add_control(
				'ma_el_before_border',
				[
					'type' => Controls_Manager::DIVIDER,
					'style' => 'thin',
					'condition' => [
						'ma_el_countdown_preset' => 'style-1',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'border',
					'label' => __( 'Border', MELA_TD ),
					'selector' => '{{WRAPPER}} .ma-el-countdown.style-1 .ma-el-countdown-container',
					'condition' => [
						'ma_el_countdown_preset' => 'style-1',
					],
				]
			);

			$this->add_control(
				'ma_el_countdown_image_border_radius',
				[
					'label' => esc_html__( 'Border Radius', MELA_TD ),
					'type' => Controls_Manager::DIMENSIONS,
					'selectors' => [
						'{{WRAPPER}} .ma-el-countdown.style-1 .ma-el-countdown-container' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
					],
					'default' => [
						'top' => 4,
						'right' => 4,
						'bottom' => 4,
						'left' => 4,
						'unit' => 'px',
						'isLinked' => true,
					],
					'condition' => [
						'ma_el_countdown_preset' => 'style-1',
					],
				]
			);


			$this->end_controls_section();

			// Counter Styles

			$this->start_controls_section(
				'ma_el_section_countdown_styles_counter',
				[
					'label' => esc_html__( 'Counter Style', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'counter_typography',
					'selector' => '{{WRAPPER}} .ma-el-countdown-count',
				]
			);

			$this->add_control(
				'ma_el_progress_bar_count_color',
				[
					'label' => __( 'Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-countdown-count' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();

			// Title Styles

			$this->start_controls_section(
				'ma_el_section_countdown_styles_title',
				[
					'label' => esc_html__( 'Title Style', MELA_TD ),
					'tab' => Controls_Manager::TAB_STYLE
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .ma-el-countdown-title',
				]
			);

			$this->add_control(
				'ma_el_progress_bar_title_color',
				[
					'label' => __( 'Color', MELA_TD ),
					'type' => Controls_Manager::COLOR,
					'default' => '#FFF',
					'selectors' => [
						'{{WRAPPER}} .ma-el-countdown-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();

		}

		protected function render() {
			$settings = $this->get_settings_for_display();

			$this->add_render_attribute(
				'ma-el-countdown-timer-attribute',
				[
					'class'    => ['ma-el-countdown', esc_attr( $settings['ma_el_countdown_preset'] )],
					'data-day'	=> esc_attr__( 'Days', MELA_TD ),
					'data-minutes' => esc_attr( 'Minutes', MELA_TD ),
					'data-hours'	=> esc_attr__( 'Hours', MELA_TD ),
					'data-seconds' => esc_attr( 'Seconds', MELA_TD ),
					'data-countdown' => esc_attr( $settings['ma_el_countdown_time'] ),
				]
			);

			?>
			<div  id ="ma-el-countdown-<?php echo esc_attr( $this->get_id() ); ?>"
			      class="ma-el-countdown-content-container">
				<div <?php echo $this->get_render_attribute_string('ma-el-countdown-timer-attribute') ?>></div>
			</div>
			<?php
		}

		protected function _content_template() {
			?>
			<div id="ma-el-countdown-timer" class="ma-el-countdown-content-container">
				<div class="ma-el-countdown {{ settings.ma_el_countdown_preset }}" data-day="Days"
				     data-minutes="Minutes"
				     data-hours="Hours" data-seconds="Seconds" data-countdown="{{ settings.ma_el_countdown_time
				     }}"></div>
			</div>
			<?php
		}


	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Countdown_Timer() );