<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Group class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;



if ( ! class_exists( 'YITH_WAPO_Settings' ) ) {

	/**
	 * Settings class
	 */
	class YITH_WAPO_Settings {

		/**
		 * Construct.
		 *
		 * @param int $id ID.
		 */
		public function __construct( $id = 0 ) {

			add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
			add_action( 'woocommerce_settings_tabs_yith_wapo_settings', __CLASS__ . '::settings_tab' );
			add_action( 'woocommerce_update_options_yith_wapo_settings', __CLASS__ . '::update_settings' );

		}

		/**
		 * Add settings tab
		 *
		 * @param array $settings_tabs Settings tabs.
		 * @return mixed
		 */
		public static function add_settings_tab( $settings_tabs ) {

			$settings_tabs['yith_wapo_settings'] = 'YITH WooCommerce Product Add-Ons'; // @since 1.1.0
			return $settings_tabs;

		}

		/**
		 * Settings tab
		 */
		public static function settings_tab() {

			woocommerce_admin_fields( self::get_settings() );
		}

		/**
		 * Update settings
		 */
		public static function update_settings() {
			woocommerce_update_options( self::get_settings() );
		}

		/**
		 * Get settings
		 *
		 * @return mixed|void
		 */
		public static function get_settings() {

			$settings = array();

			return apply_filters( 'wc_yith_wapo_settings_settings', $settings );

		}

	}

}
