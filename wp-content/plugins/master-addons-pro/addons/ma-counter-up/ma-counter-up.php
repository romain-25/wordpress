<?php
	namespace Elementor;
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/27/19
	 */

	use Elementor\Widget_Base;

	if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


	class Master_Addons_Counter_Up extends Widget_Base {

		//use ElementsCommonFunctions;
		public function get_name() {
			return 'ma-el-counter-up';
		}
		public function get_title() {
			return esc_html__( 'MA Counter Up', MELA_TD );
		}
		public function get_icon() {
			return 'ma-el-icon eicon-counter';
		}
		public function get_categories() {
			return [ 'master-addons' ];
		}

		public function get_script_depends() {
			return [
				'ma-counter-up',
				'master-addons-waypoints',
				'master-addons-scripts'
			];
		}


		protected function _register_controls() {
			$this->start_controls_section(
				'ma_el_counterup_section_start',
				[
					'label' => __( 'Counter Up Setting', MELA_TD )
				]
			);


		}


		protected function render() {

			echo "Counter Up !!!";
//
//			$settings  = $this->get_settings_for_display();
//			$extra_css = $settings['extra_css'];
//			if($extra_css){
//				$extra_css = $extra_css.' ';
//			}
//
//			if( is_array( $settings['wpb_ea_counterup_lists'] ) ):
//				$column = 12/$settings['column'];
//				$column = 'col-lg-'.esc_attr( $column ). ' col-md-6';
//				echo '<div class="'.esc_attr( $extra_css ).'wpb-ea-counterup-items ea-row">';
//				foreach ( $settings['wpb_ea_counterup_lists'] as $list ) :
//					echo '<div class="'.esc_attr($column).'">';
//					echo '<div class="wpb-ea-counterup wpb-ea-counterup-icon-'.esc_attr($settings['wpb_ea_counterup_icon_align']).'">';
//					echo '<span class="wpb-ea-counterup-icon counterup-icon-text-'.esc_attr($settings['wpb_ea_counterup_icon_align']).'">';
//					if ( ! empty( $list['icon'] ) && ( $list['icon_type'] == 'icon' ) ) :
//						echo '<i class="'.esc_attr( $list['icon'] ).'"></i>';
//					endif;
//					if ( ! empty( $list['custom'] ) && ( $list['icon_type'] == 'custom' ) ) :
//						echo wp_get_attachment_image( $list['custom']['id'], 'thumbnail' );
//					endif;
//					echo '</span>';
//					if ( ! empty( $list['number'] ) || ( $list['title'] ) ) :
//						echo '<div class="wpb-ea-counterup-content">';
//						$list['number'] ? printf('<h3 class="wpb-ea-counterup-number">%s</h3>', esc_html($list['number']) ) : '';
//						$list['title'] ? printf('<span class="wpb-ea-counterup-title">%s</span>', esc_html($list['title']) ) : '';
//						echo '</div>';
//					endif;
//					echo '</div>';
//					echo '</div>';
//				endforeach;
//				echo '</div>';
//			endif;

		}


	}

	Plugin::instance()->widgets_manager->register_widget_type( new Master_Addons_Counter_Up() );
