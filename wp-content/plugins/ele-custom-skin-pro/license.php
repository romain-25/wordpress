<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Include the dependencies needed to instantiate the plugin.
foreach ( glob( plugin_dir_path( __FILE__ ) . 'admin/*.php' ) as $file ) {
    include_once $file;
}
//include_once( plugin_dir_path( __FILE__ ) . 'shared/class-deserializer.php' );
add_action( 'plugins_loaded', 'elecs_custom_admin_settings' );
/**
 * Starts the plugin.
 *
 * @since 1.0.0
 */
function elecs_custom_admin_settings() {
    
    $elecs_plugin_remote_path = 'https://dudaster.com/license.php';
    $license = new ecs_license($elecs_plugin_remote_path, ELECSP_PID);
    $serializer = new ecs_serializer($license);
    $serializer->init();
    $plugin = new ecs_submenu( new ecs_license_page($license));
    $plugin->init();
 
}