<?php
	namespace MasterAddons\AddonsManager;

	use MasterAddons\Base\Module_Base;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Modules_Manager {
		/**
		 * @var Module_Base[]
		 */
		private $modules = [];

		public function register_modules() {
			$modules = [

				'business-hours',
			];

			ksort($modules);

			foreach ( $modules as $module_name ) {
print_r($module_name);

				$class_name = str_replace( '-', ' ', $module_name );

				$class_name = str_replace( ' ', '', ucwords( $class_name ) );

				$class_name = __NAMESPACE__ . '\\Addons\\' . $class_name . '\Addons';

				/** @var Module_Base $class_name */
				if ( $class_name::is_active() ) {
					$this->modules[ $module_name ] = $class_name::instance();
				}
			}
		}

		/**
		 * @param string $module_name
		 *
		 * @return Module_Base|Module_Base[]
		 */
		public function get_modules( $module_name ) {
			if ( $module_name ) {
				if ( isset( $this->modules[ $module_name ] ) ) {
					return $this->modules[ $module_name ];
				}

				return null;
			}

			return $this->modules;
		}

		private function require_files() {
			require( \Master_Elementor_Addons::mela_plugin_path() . '/inc/base/master-base.php' );
		}

		public function __construct() {
			$this->require_files();
			$this->register_modules();
		}
	}