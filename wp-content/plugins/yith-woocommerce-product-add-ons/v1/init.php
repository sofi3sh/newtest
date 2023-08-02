<?php
/**
 * Version 1.x
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

// Check if a free version currently active and try disabling before activating this one.
if ( defined( 'YITH_WAPO_PREMIUM' ) ) {
	if ( ! function_exists( 'yit_deactive_free_version' ) ) {
		require_once YITH_WAPO_DIR . 'plugin-fw/yit-deactive-plugin.php';
	}
	yit_deactive_free_version( 'YITH_WCCL_FREE_INIT', plugin_basename( __FILE__ ) );
	yit_deactive_free_version( 'YITH_WAPO_FREE_INIT', plugin_basename( __FILE__ ) );
}

// Plugin Framework Version Check.
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WAPO_DIR . 'plugin-fw/init.php' ) ) {
	require_once YITH_WAPO_DIR . 'plugin-fw/init.php';
}
yit_maybe_plugin_fw_loader( YITH_WAPO_DIR );

// Plugin registration.
if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once YITH_WAPO_DIR . 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

// Main Product Add-ons functions.
require_once YITH_WAPO_DIR . 'v1/includes/functions/yith-wapo.php';

// Init.
add_action( 'plugins_loaded', 'yith_wapo_init', 12 );
