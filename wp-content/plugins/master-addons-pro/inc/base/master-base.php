<?php

namespace MasterAddons\Base;

use Elementor\Widget_Base;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


abstract class Master_Addons_Widget extends Widget_Base {

	public function get_categories() {
		return [ 'master-addons' ];
	}
}
