<?php
/*
 * Plugin Name: Ele Custom Skin Pro
 * Version: 1.3.2
 * Description: Elementor Custom Skin Pro adds more functionality to the Ele Custom Skin: alternating templates inside a loop, dynamic anywhere, custom dynamic values and many more.
 * Plugin URI: https://dudaster.com
 * Author: Dudaster.com
 * Author URI: https://dudaster.com
 * Text Domain: ele-custom-skin-pro
 * Domain Path: /languages
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'ELECSP_DIR', plugin_dir_path( __FILE__ ));
define ('ELECSP_VER','1.3.2');
define ('ELECSP_PID','ecsprou');
define( 'ELECSP_URL', plugin_dir_url( __FILE__ ));
add_action('elementor/widgets/widgets_registered','ele_custom_skin_pro');
update_option( 'elecs-license-key', 'dadadada' );
function ele_custom_skin_pro(){
	require_once ELECSP_DIR.'skin/skin-custom.php';
}

add_action('init', 'elecs_activate_au');
function elecs_activate_au()
{ 
  require_once (ELECSP_DIR.'update.php');
  $elecs_plugin_current_version = ELECSP_VER;
  $elecs_plugin_remote_path = 'https://dudaster.com/update.php';
  $elecs_plugin_slug = plugin_basename(__FILE__);
  new ecs_update ($elecs_plugin_current_version, $elecs_plugin_remote_path, $elecs_plugin_slug);
}

require_once (ELECSP_DIR.'license.php');

function elecspro_action_links( $links ) {
	$links = array_merge($links, array(
		'<a href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=theme&elementor_library_type=loop' ) ) . '">' . __( 'Add Loop Template', 'ele-custom-skin' ) . '</a>',
	));
  
  if (!get_option(  'elecs-license-key', '')) $links = array_merge($links, array(
      '<a href="' . esc_url( admin_url( '/options-general.php?page=elecsp-admin-page' ) ) . '" aria-label="' . esc_attr__( 'Activate License!', 'ele-custom-skin' ) . '" style="color:red;font-weight:bold;">' . esc_html__( 'Activate License!', 'ele-custom-skin' ) . '</a>'
	));
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'elecspro_action_links' );

foreach ( glob( plugin_dir_path( __FILE__ ) . 'modules/*.php' ) as $file ) {
      include_once $file;
}