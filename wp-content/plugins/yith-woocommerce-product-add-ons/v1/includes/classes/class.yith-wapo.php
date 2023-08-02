<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Main class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons Premium
 */

if ( ! defined( 'YITH_WAPO' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WAPO' ) ) {
	/**
	 * YITH WooCommerce Ajax Navigation
	 *
	 * @since 1.0.0
	 */
	class YITH_WAPO {
		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version;

		/**
		 * Frontend object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $frontend = null;

		/**
		 * Admin object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $admin = null;

		/**
		 * Main instance
		 *
		 * @var string
		 * @since 1.4.0
		 */
		protected static $instance = null;


		/**
		 * Check if YITH Multi Vendor is installed
		 *
		 * @var boolean
		 * @since 1.0.0
		 */
		public static $is_vendor_installed;

		/**
		 * Check if WPML is installed
		 *
		 * @var boolean
		 * @since 1.0.0
		 */
		public static $is_wpml_installed;


		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			$this->version = YITH_WAPO_VERSION;

			self::$is_vendor_installed = function_exists( 'YITH_Vendors' );

			global $sitepress;
			self::$is_wpml_installed = ! empty( $sitepress );

			/* Load Plugin Framework */
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );
			add_action( 'plugins_loaded', array( $this, 'load_privacy' ), 20 );

			$this->create_tables();
			$this->required();
			$this->init();

			add_filter( 'sanitize_text_field', array( $this, 'my_sanitize_text_field' ), 10, 2 );

			// Prevent skip add-ons with values already in the product details area of the product name
			// add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );.

			// Divi ET Builder Module integration.
			add_action( 'et_builder_ready', array( $this, 'divi_et_builder_module_integration' ) );

			// Register plugin to licence/update system.
			if ( defined( 'YITH_WAPO_PREMIUM' ) && YITH_WAPO_PREMIUM ) {
				add_action( 'wp_loaded', array( $this, 'register_plugin_for_activation' ), 99 );
				add_action( 'admin_init', array( $this, 'register_plugin_for_updates' ) );
			}

		}

		/**
		 * Register plugins for activation tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_activation() {

			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once YITH_WAPO_DIR . 'plugin-fw/licence/lib/yit-licence.php';
				require_once YITH_WAPO_DIR . 'plugin-fw/licence/lib/yit-plugin-licence.php';
			}

			YIT_Plugin_Licence()->register( YITH_WAPO_INIT, YITH_WAPO_SECRET_KEY, YITH_WAPO_SLUG );
		}

		/**
		 * Register plugins for update tab
		 *
		 * @return void
		 * @since 2.0.0
		 */
		public function register_plugin_for_updates() {

			if ( ! class_exists( 'YIT_Plugin_Licence' ) ) {
				require_once YITH_WAPO_DIR . 'plugin-fw/lib/yit-upgrade.php';
			}

			YIT_Upgrade()->register( YITH_WAPO_SLUG, YITH_WAPO_INIT );
		}

		/**
		 * Fix: Uploaded files link in order details
		 *
		 * @param string $filtered Filtered.
		 * @param string $str String.
		 */
		public function my_sanitize_text_field( $filtered, $str ) {
			if ( is_string( $str ) && strpos( $str, 'uploads' ) ) {
				return $str; } else {
				return $filtered; }
		}

		/**
		 * Load plugin framework
		 *
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 * @since  1.0
		 * @return void
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Load Privacy
		 */
		public function load_privacy() {
			require_once YITH_WAPO_DIR . 'v1/includes/classes/class.yith-wapo-privacy.php';
		}

		/**
		 * Main plugin Instance
		 *
		 * @return YITH_WAPO Main instance
		 * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
		 */
		public static function instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new YITH_WAPO();
			}

			return self::$instance;
		}

		/**
		 * Create tables
		 */
		public static function create_tables() {

			$yith_wapo_db_version = apply_filters( 'yith_wapo_db_version', get_option( 'yith_wapo_db_version' ) );

			if ( YITH_WAPO_DB_VERSION !== $yith_wapo_db_version ) {

				YITH_WAPO_Group::create_tables();
				YITH_WAPO_Type::create_tables();
				update_option( 'yith_wapo_db_version', YITH_WAPO_DB_VERSION );

			}

		}


		/**
		 * Load required files
		 *
		 * @since 1.4
		 * @return void
		 * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
		 */
		public function required() {
			$required = apply_filters(
				'yith_wapo_required_files',
				array(
					'v1/includes/classes/class.yith-wapo-admin.php',
					'v1/includes/classes/class.yith-wapo-frontend.php',
					'v1/includes/functions/yith-wapo-database.php',
				)
			);

			if ( self::$is_wpml_installed ) {
				$required[] = 'v1/includes/classes/class.yith-wapo-wpml.php';
			}

			foreach ( $required as $file ) {
				file_exists( YITH_WAPO_DIR . $file ) && require_once YITH_WAPO_DIR . $file;
			}
		}

		/**
		 * Init
		 */
		public function init() {

			if ( is_admin() ) {
				$this->admin = new YITH_WAPO_Admin( $this->version );
			}

			$this->frontend = new YITH_WAPO_Frontend( $this->version );

		}

		/**
		 * Is Quick View
		 */
		private function is_quick_view() {

			$nonce = ! function_exists( 'wp_verify_nonce' ) || isset( $_REQUEST['action'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['action'] ), 'action' );
			if ( $nonce ) {
				$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
				$action     = isset( $_REQUEST['action'] ) && (
					'yit_load_product_quick_view' === $_REQUEST['action'] ||
					'yith_load_product_quick_view' === $_REQUEST['action'] ||
					'ux_quickview' === $_REQUEST['action']
					);
				return $doing_ajax && $action;
			}
		}

		/**
		 * Get Allowed Product Types
		 */
		public static function getAllowedProductTypes() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid

			return apply_filters( 'yith_wapo_product_type_list', array( 'simple', 'variable', 'grouped', 'bundle', 'booking', 'subscription', 'variable-subscription' ) );

		}

		/**
		 * Get Current Multivendor
		 */
		public static function get_current_multivendor() {

			if ( self::$is_vendor_installed && is_user_logged_in() ) {

				$vendor = yith_get_vendor( 'current', 'user' );

				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}

			return null;
		}

		/**
		 * Get Multivendor by ID
		 *
		 * @param int    $id ID.
		 * @param string $obj Obj.
		 */
		public static function get_multivendor_by_id( $id, $obj = 'vendor' ) {

			if ( self::$is_vendor_installed ) {

				$vendor = yith_get_vendor( $id, $obj );

				if ( $vendor->is_valid() ) {
					return $vendor;
				}
			}

			return null;
		}

		/**
		 * Is plugin enabled for vendors
		 */
		public static function is_plugin_enabled_for_vendors() {
			return get_option( 'yith_wpv_vendors_option_advanced_product_options_management' ) === 'yes';
		}

		/**
		 * Divi ET Builder Module Integration
		 */
		public function divi_et_builder_module_integration() {
			if ( class_exists( 'ET_Builder_Module' ) ) {
				include YITH_WAPO_DIR . 'v1/includes/integrations/class.divi-et-builder_module.php';
			}
		}

	}
}
