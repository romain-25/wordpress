<?php

/**
 * Plugin Name: Master Addons for Elementor (Pro)
 * Description: Master Addons is easy and must have Elementor Addons for WordPress Page Builder. Clean, Modern, Hand crafted designed Addons blocks.
 * Plugin URI: https://master-addons.com
 * Author: Jewel Theme
 * Version: 1.2.1
 * Author URI: https://master-addons.com
 * Text Domain: mela
 * Domain Path: /languages
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// No, Direct access Sir !!!

if ( !function_exists( 'ma_el_fs' ) ) {
    // Create a helper function for easy SDK access.
    function ma_el_fs()
    {
        global  $ma_el_fs ;
        
        if ( !isset( $ma_el_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_4015_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_4015_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/lib/freemius/start.php';
            $ma_el_fs = fs_dynamic_init( array(
                'id'              => '4015',
                'slug'            => 'master-addons',
                'type'            => 'plugin',
                'public_key'      => 'pk_3c9b5b4e47a06288e3500c7bf812e',
                'is_premium'      => true,
                'has_affiliation' => 'all',
                'has_addons'      => false,
                'has_paid_plans'  => false,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => false,
            ),
                'menu'            => array(
                'slug'       => 'master-addons-settings',
                'first-path' => 'admin.php?page=master-addons-settings',
            ),
                'is_live'         => true,
            ) );
        }
        
        return $ma_el_fs;
    }
    
    // Init Freemius.
    ma_el_fs();
    // Signal that SDK was initiated.
    do_action( 'ma_el_fs_loaded' );
}

//	//Instantiate Master Addons Class
require_once dirname( __FILE__ ) . '/class-master-elementor-addons.php';
function ma_el_fs_add_licensing_helper()
{
    ?>
        <script type="text/javascript">
            (function () {
                window.ma_el_fs = { can_use_premium_code: <?php 
    echo  json_encode( ma_el_fs()->can_use_premium_code() ) ;
    ?>};
            })();
        </script>
		<?php 
}

add_action( 'wp_head', 'ma_el_fs_add_licensing_helper' );
//
//	if ( ! ma_el_fs()->is_premium() ) {
//		require_once dirname(__FILE__) . 'path/to/free/loader.php';
//	}
//
//	if ( ma_el_fs()->is__premium_only() ) {
//	    if ( ma_el_fs()->can_use_premium_code() ) {
//		    require_once dirname(__FILE__) . 'path/to/premium/loader.php';
//	    }
//    }
// Customize Opt-in Message for Existing Users
function ma_el_fs_custom_connect_message_on_update(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
)
{
    return sprintf(
        __( 'Hey %1$s' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'master-addons' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

ma_el_fs()->add_filter(
    'connect_message_on_update',
    'ma_el_fs_custom_connect_message_on_update',
    10,
    6
);
// Not like register_uninstall_hook(), you do NOT have to use a static function.
ma_el_fs()->add_action( 'after_uninstall', 'ma_el_fs_uninstall_cleanup' );
function ma_el_fs_add_helpscount_permission( $permissions )
{
    $permissions['helpscout'] = array(
        'icon-class' => 'dashicons dashicons-email-alt',
        'label'      => ma_el_fs()->get_text_inline( 'Help Scout', 'helpscout' ),
        'desc'       => ma_el_fs()->get_text_inline( 'Rendering Help Scout\'s beacon for easy support access', 'permissions-helpscout' ),
        'priority'   => 16,
    );
    $permissions['newsletter'] = array(
        'icon-class' => 'dashicons dashicons-email-alt',
        'label'      => ma_el_fs()->get_text_inline( 'Newsletter', 'permissions-newsletter' ),
        'desc'       => ma_el_fs()->get_text_inline( 'Updates, announcements, marketing, no spam', 'permissions-newsletter_desc' ),
        'priority'   => 15,
    );
}

ma_el_fs()->add_filter( 'permissions_list', 'ma_el_fs_add_helpscount_permission' );
//Controlling the visibility of admin notices added by the Freemius SDK
/**
 * @param bool  $show
 * @param array $msg {
 *     @var string $message The actual message.
 *     @var string $title An optional message title.
 *     @var string $type The type of the message ('success', 'update', 'warning', 'promotion').
 *     @var string $id The unique identifier of the message.
 *     @var string $manager_id The unique identifier of the notices manager. For plugins it would be the plugin's slug, for themes - `<slug>-theme`.
 *     @var string $plugin The product's title.
 *     @var string $wp_user_id An optional WP user ID that this admin notice is for.
 * }
 * @return bool
 */
function ma_el_fs_custom_show_admin_notice( $show, $msg )
{
    if ( 'trial_promotion' == $msg['id'] ) {
        // Don't show the trial promotional admin notice.
        return false;
    }
    return $show;
}

ma_el_fs()->add_filter(
    'show_admin_notice',
    'ma_el_fs_custom_show_admin_notice',
    10,
    2
);
// Freemius Purchase Completion JavaScript Callback Filter
function ma_el_fs_after_purchase_js( $js_function )
{
    return 'function (data) {
            console.log("checkout", "purchaseCompleted");
        }';
}

ma_el_fs()->add_filter( 'checkout/purchaseCompleted', 'ma_el_fs_after_purchase_js' );
// Freemius submenu items visibility filter
function ma_el_fs_is_submenu_visible( $is_visible, $submenu_id )
{
    return $is_visible;
}

ma_el_fs()->add_filter(
    'is_submenu_visible',
    'ma_el_fs_is_submenu_visible',
    10,
    2
);
// Trial
ma_el_fs()->override_i18n( array(
    'hey'                                        => 'Hey',
    'trial-x-promotion-message'                  => 'Thank you so much for using %s!',
    'already-opted-in-to-product-usage-tracking' => 'How do you like %s so far? Test all our %s premium features with a %d-day free trial.',
    'start-free-trial'                           => 'Start free trial',
    'no-commitment-for-x-days'                   => 'No commitment for %s days - cancel anytime!',
    'no-cc-required'                             => 'No credit card required',
) );
#----------------------------------------------------------------------------------
#region Show the 1st trial promotion after 7 days instead of 24 hours.
#----------------------------------------------------------------------------------
function ma_el_fs_show_first_trial_after_7_days( $day_in_sec )
{
    // 7 days in sec.
    return 7 * 24 * 60 * 60;
}

ma_el_fs()->add_filter( 'show_first_trial_after_n_sec', 'ma_el_fs_show_first_trial_after_7_days' );
#----------------------------------------------------------------------------------
#region Re-show the trial promotional offer after every 60 days instead of 30 days.
#----------------------------------------------------------------------------------
function ma_el_fs_reshow_trial_after_every_60_days( $thirty_days_in_sec )
{
    // 60 days in sec.
    return 60 * 24 * 60 * 60;
}

ma_el_fs()->add_filter( 'reshow_trial_after_every_n_sec', 'ma_el_fs_reshow_trial_after_every_60_days' );