<?php
/**
 * WAPO Admin Class
 *
 * @author  Corrado Porzio <corradoporzio@gmail.com>
 * @package YITH\ProductAddOns
 * @version 2.0.0
 */

defined( 'YITH_WAPO' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_WAPO_Admin' ) ) {

	/**
	 *  Admin class.
	 *  The class manage all the admin behaviors.
	 */
	class YITH_WAPO_Admin {

		/**
		 * Single instance of the class
		 *
		 * @var YITH_WAPO_Admin
		 */
		protected static $instance;

		/**
		 * Plugin options
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		public $version = YITH_WAPO_VERSION;

		/**
		 * The plugin panel
		 *
		 * @var YIT_Plugin_Panel_WooCommerce
		 */
		protected $panel;

		/**
		 * Premium version landing link
		 *
		 * @var string
		 */
		protected $premium_landing = 'https://yithemes.com/themes/plugins/yith-woocommerce-product-add-ons/';

		/**
		 * Panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_wapo_panel';

		/**
		 * Documentation URL
		 *
		 * @var string
		 */
		public $doc_url = 'https://docs.yithemes.com/yith-woocommerce-product-add-ons/';

		/**
		 * Demo URL
		 *
		 * @var string
		 */
		public $demo_url = 'https://plugins.yithemes.com/yith-woocommerce-product-add-ons/product/custom-post/';

		/**
		 * YITH Site URL
		 *
		 * @var string
		 */
		public $yith_url = 'https://www.yithemes.com';

		/**
		 * Landing URL
		 *
		 * @var string
		 */
		public $plugin_url = 'https://yithemes.com/themes/plugins/yith-woocommerce-product-add-ons/';

		/**
		 * Returns single instance of the class
		 *
		 * @return YITH_WAPO_Admin | YITH_WAPO_Admin_Premium
		 */
		public static function get_instance() {
			$self = __CLASS__ . ( class_exists( __CLASS__ . '_Premium' ) ? '_Premium' : '' );
			return ! is_null( $self::$instance ) ? $self::$instance : $self::$instance = new $self();
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// Admin menu.
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
			add_action( 'admin_menu', array( $this, 'old_admin_menu' ), 10 );

			// Add action links.
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_WAPO_DIR . '/' . basename( YITH_WAPO_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );

			// Enqueue scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Blocks Settings.
			add_action( 'yith_wapo_show_blocks_tab', array( $this, 'show_blocks_tab' ) );
			add_action( 'yith_wapo_impexp_tab', array( $this, 'show_impexp_tab' ) );

			// Premium Tabs.
			add_action( 'yith_wapo_premium_tab', array( $this, 'show_premium_tab' ) );

			// Debug Tabs.
			add_action( 'yith_wapo_debug_tab', array( $this, 'show_debug_tab' ) );

			// Update visibility (enable/disable).
			add_action( 'wp_ajax_enable_disable_block', array( $this, 'enable_disable_block' ) );
			add_action( 'wp_ajax_nopriv_enable_disable_block', array( $this, 'enable_disable_block' ) );
			add_action( 'wp_ajax_enable_disable_addon', array( $this, 'enable_disable_addon' ) );
			add_action( 'wp_ajax_nopriv_enable_disable_addon', array( $this, 'enable_disable_addon' ) );

			// Save sortable items.
			add_action( 'wp_ajax_sortable_blocks', array( $this, 'sortable_blocks' ) );
			add_action( 'wp_ajax_nopriv_sortable_blocks', array( $this, 'sortable_blocks' ) );
			add_action( 'wp_ajax_sortable_addons', array( $this, 'sortable_addons' ) );
			add_action( 'wp_ajax_nopriv_sortable_addons', array( $this, 'sortable_addons' ) );

			// WooCommerce Product Data Tab.
			add_action( 'admin_init', array( $this, 'add_wc_product_data_tab' ) );
			add_action( 'woocommerce_process_product_meta', array( $this, 'woo_add_custom_general_fields_save' ) );
		}

		/**
		 * Return an array of links for the YITH Sidebar
		 *
		 * @return array
		 */
		public function get_panel_sidebar_links() {
			$links = array(
				array(
					'url'   => $this->yith_url,
					'title' => __( 'Your Inspiration Themes', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'url'   => $this->doc_url,
					'title' => __( 'Plugin Documentation', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'url'   => $this->plugin_url,
					'title' => __( 'Plugin Site', 'yith-woocommerce-product-add-ons' ),
				),
				array(
					'url'   => $this->demo_url,
					'title' => __( 'Live Demo', 'yith-woocommerce-product-add-ons' ),
				),
			);

			return $links;
		}

		/**
		 * Action Links
		 * add the action links to plugin admin page
		 *
		 * @param array $links Action links.
		 *
		 * @use     plugin_action_links_{$plugin_file_name}
		 * @Return  array
		 * @author  Leanza Francesco <leanzafrancesco@gmail.com>
		 */
		public function action_links( $links ) {
			return yith_add_action_links( $links, $this->panel_page, defined( 'YITH_WAPO_PREMIUM' ), YITH_WAPO_SLUG );
		}

		/**
		 * Adds action links to plugin admin page
		 *
		 * @param array    $row_meta_args Row meta arguments.
		 * @param string[] $plugin_meta   An array of the plugin's metadata,
		 *                                including the version, author,
		 *                                author URI, and plugin URI.
		 * @param string   $plugin_file   Path to the plugin file relative to the plugins directory.
		 * @param array    $plugin_data   An array of plugin data.
		 * @param string   $status        Status of the plugin. Defaults are 'All', 'Active',
		 *                                'Inactive', 'Recently Activated', 'Upgrade', 'Must-Use',
		 *                                'Drop-ins', 'Search', 'Paused'.
		 *
		 * @return array
		 */
		public function plugin_row_meta( $row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( YITH_WAPO_INIT === $plugin_file ) {
				$row_meta_args['slug']       = YITH_WAPO_SLUG;
				$row_meta_args['is_premium'] = true;
			}
			return $row_meta_args;
		}

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    1.0
		 * @author   Leanza Francesco <leanzafrancesco@gmail.com>
		 * @use      YIT_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$capability  = 'manage_woocommerce';
			$parent_page = 'yit_plugin_panel';
			$vendor      = '';

			if ( class_exists( 'YITH_Vendors' ) ) {
				$vendor                = yith_get_vendor( 'current', 'user' );
				$is_enabled_for_vendor = get_option( 'yith_wpv_vendors_option_advanced_product_options_management' ) === 'yes';
				if ( $is_enabled_for_vendor && $vendor->is_valid() && $vendor->has_limited_access() ) {
					$capability  = YITH_Vendors()->admin->get_special_cap();
					$parent_page = 'edit.php?post_type=product';
				}
			}

			$tabs = array(
				'blocks' => __( 'Options Blocks', 'yith-woocommerce-product-add-ons' ),
			);
			if ( 'manage_woocommerce' === $capability ) {
				$tabs['settings'] = __( 'General Settings', 'yith-woocommerce-product-add-ons' );
				$tabs['style']    = __( 'Style', 'yith-woocommerce-product-add-ons' );
				// $tabs['impexp'] = __( 'Import / Export', 'yith-woocommerce-product-add-ons' ); phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				if ( isset( $_REQUEST['tab'] ) && 'debug' === $_REQUEST['tab'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$tabs['debug'] = __( 'Debug', 'yith-woocommerce-product-add-ons' );
				}
			}
			if ( ! defined( 'YITH_WAPO_PREMIUM' ) || ! YITH_WAPO_PREMIUM ) {
				$tabs['premium'] = __( 'Premium Version', 'yith-woocommerce-product-add-ons' );
			}

			$args = array(
				'create_menu_page' => true,
				'class'            => yith_set_wrapper_class(),
				'parent_slug'      => '',
				'plugin_slug'      => YITH_WAPO_SLUG,
				'page_title'       => 'YITH WooCommerce Product Add-ons & Extra Options',
				'menu_title'       => 'Product Add-ons & Extra Options',
				'capability'       => apply_filters( 'yith_wapo_register_panel_capabilities', $capability ),
				'parent'           => YITH_WAPO_SLUG,
				'parent_page'      => $parent_page,
				'page'             => $this->panel_page,
				'links'            => $this->get_panel_sidebar_links(),
				'admin-tabs'       => $tabs,
				'plugin-url'       => YITH_WAPO_DIR,
				'options-path'     => YITH_WAPO_DIR . 'plugin-options',
				'help_tab'         => array(
					'main_video' => array(
						'desc' => _x( 'Check this video to learn how to <b>create an options block and show it in a product page:</b>', '[HELP TAB] Video title', 'yith-woocommerce-product-add-ons' ),
						'url'  => array(
							'en' => 'https://www.youtube.com/embed/EGjhyE3u_30',
							'it' => 'https://www.youtube.com/embed/EEC3YEPUeCQ',
							'es' => 'https://www.youtube.com/embed/DnZxmLV1874',
						),
					),
					'playlists'  => array(
						'en' => 'https://www.youtube.com/watch?v=v5JTUCmPUyQ&list=PLDriKG-6905ksfE-ofI5k1iu1D6NVzi3I',
						'it' => 'https://www.youtube.com/watch?v=gV5pa5KYfaA&list=PL9c19edGMs09Lzsq-rvTm-6fgb6WhdRJX',
						'es' => 'https://www.youtube.com/watch?v=N50b2nlT_YA&list=PL9Ka3j92PYJPJSgfgSWWeVXg2xQHYLx4a',
					),
					'hc_url'     => 'https://support.yithemes.com/hc/en-us/categories/360003474698-YITH-WOOCOMMERCE-PRODUCT-ADD-ONS',
				),
			);

			//Todo: add the premium tab as $args['premium_tab']
			if ( ( ! defined( 'YITH_WAPO_PREMIUM' ) || ! YITH_WAPO_PREMIUM ) || class_exists( 'YITH_Vendors' ) && $vendor instanceof YITH_Vendor && $vendor->is_valid() ) {
				unset( $args['help_tab'] );
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Temporary admin link for the 1.x version
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function old_admin_menu() {
			$page = add_submenu_page(
				'edit.php?post_type=product',
				'Add-ons',
				'Add-ons',
				'manage_woocommerce',
				'admin.php?page=yith_wapo_panel'
			);
		}

		/**
		 * Admin enqueue scripts
		 */
		public function admin_enqueue_scripts() {

			if ( isset( $_GET['page'] ) && 'yith_wapo_panel' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				$screen = get_current_screen();
				$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				// CSS.
				wp_enqueue_style( 'wapo-admin', YITH_WAPO_URL . 'assets/css/admin.css', false, wp_rand( 10000, 99999 ) );

				// JS.
				wp_register_script( 'yith_wapo_admin', YITH_WAPO_URL . 'assets/js/admin' . $suffix . '.js', array( 'jquery' ), wp_rand( 10000, 99999 ), true );
				wp_enqueue_script( 'yith_wapo_admin' );

			}

		}

		/**
		 * Add management capabilities to Admin and Shop Manager
		 */
		public function add_capabilities() {
			$caps = yith_wapo_create_capabilities( array( 'addon', 'addons' ) );

			$admin        = get_role( 'administrator' );
			$shop_manager = get_role( 'shop_manager' );

			foreach ( $caps as $cap => $value ) {
				if ( $admin ) {
					$admin->add_cap( $cap );
				}

				if ( $shop_manager ) {
					$shop_manager->add_cap( $cap );
				}
			}
		}

		/**
		 * Show blocks tab
		 *
		 * @return  void
		 */
		public function show_blocks_tab() {
			$template = YITH_WAPO_TEMPLATE_PATH . '/admin/blocks.php';
			file_exists( $template ) && require $template;
		}

		/**
		 * Show blocks tab
		 *
		 * @return  void
		 */
		public function show_impexp_tab() {
			$template = YITH_WAPO_TEMPLATE_PATH . '/admin/impexp.php';
			file_exists( $template ) && require $template;
		}

		/**
		 * Show premium landing tab
		 *
		 * @return void
		 */
		public function show_premium_tab() {
			$template = YITH_WAPO_TEMPLATE_PATH . '/admin/premium.php';
			file_exists( $template ) && require $template;
		}


		/**
		 * Show debug tab
		 *
		 * @return void
		 */
		public function show_debug_tab() {
			$template = YITH_WAPO_TEMPLATE_PATH . '/admin/debug.php';
			file_exists( $template ) && require $template;
		}

		/**
		 * Get the premium landing uri
		 *
		 * @return string The premium landing link.
		 */
		public function get_premium_landing_uri() {
			return apply_filters( 'yith_plugin_fw_premium_landing_uri', $this->premium_landing, YITH_WAPO_SLUG );
		}

		/**
		 * Update block visibility
		 *
		 * @return void
		 */
		public function enable_disable_block() {
			global $wpdb;
			$block_id  = isset( $_POST['block_id'] ) ? floatval( $_POST['block_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$block_vis = isset( $_POST['block_vis'] ) ? floatval( $_POST['block_vis'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			// Update db table.
			$table = $wpdb->prefix . 'yith_wapo_blocks';
			$data  = array( 'visibility' => $block_vis );
			$wpdb->update( $table, $data, array( 'id' => $block_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			wp_die();
		}

		/**
		 * Update addon visibility
		 *
		 * @return void
		 */
		public function enable_disable_addon() {
			global $wpdb;
			$addon_id  = isset( $_POST['addon_id'] ) ? floatval( $_POST['addon_id'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$addon_vis = isset( $_POST['addon_vis'] ) ? floatval( $_POST['addon_vis'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			// Update db table.
			$table = $wpdb->prefix . 'yith_wapo_addons';
			$data  = array( 'visibility' => $addon_vis );
			$wpdb->update( $table, $data, array( 'id' => $addon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			wp_die();
		}

		/**
		 * Sort blocks list
		 *
		 * @return void
		 */
		public function sortable_blocks() {
			global $wpdb;

			$moved_item = isset( $_POST['moved_item'] ) ? floatval( $_POST['moved_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$prev_item  = isset( $_POST['prev_item'] ) ? floatval( $_POST['prev_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$next_item  = isset( $_POST['next_item'] ) ? floatval( $_POST['next_item'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			if ( $prev_item === $next_item || $prev_item > $next_item ) {
				$next_item = $prev_item + 1;
			}

			$gap      = $next_item - $prev_item;
			$med      = floatval( $gap / 2 );
			$priority = $prev_item + $med;

			// Update db table.
			$table = $wpdb->prefix . 'yith_wapo_blocks';
			$data  = array( 'priority' => $priority );
			$wpdb->update( $table, $data, array( 'id' => $moved_item ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

			echo esc_attr( $moved_item . '-' . $priority );

			wp_die();
		}

		/**
		 * Sort addons list
		 *
		 * @return void
		 */
		public function sortable_addons() {
			global $wpdb;

			$moved_item = isset( $_POST['moved_item'] ) ? floatval( $_POST['moved_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$prev_item  = isset( $_POST['prev_item'] ) ? floatval( $_POST['prev_item'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$next_item  = isset( $_POST['next_item'] ) ? floatval( $_POST['next_item'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification.Missing

			if ( $prev_item === $next_item || $prev_item > $next_item ) {
				$next_item = $prev_item + 1;
			}

			$gap      = $next_item - $prev_item;
			$med      = floatval( $gap / 2 );
			$priority = $prev_item + $med;

			// Update db table.
			$table = $wpdb->prefix . 'yith_wapo_addons';
			$data  = array( 'priority' => $priority );
			$wpdb->update( $table, $data, array( 'id' => $moved_item ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

			echo esc_attr( $moved_item . '-' . $priority );

			wp_die();
		}

		/**
		 * Add WC Product Data Tab
		 *
		 * @return void
		 */
		public static function add_wc_product_data_tab() {

			$current_vendor = YITH_WAPO::get_current_multivendor();
			if ( isset( $current_vendor ) && is_object( $current_vendor ) && $current_vendor->has_limited_access() && ! YITH_WAPO::is_plugin_enabled_for_vendors() ) {
				return;
			}

			add_filter( 'woocommerce_product_data_tabs', 'wapo_product_data_tab' );
			if ( ! function_exists( 'wapo_product_data_tab' ) ) {
				/**
				 * WooCommerce Product Data Tabs action
				 *
				 * @param array $product_data_tabs Product data tabs.
				 * @return mixed
				 */
				function wapo_product_data_tab( $product_data_tabs ) {
					global $post;

					if ( $post && isset( $post->ID ) ) {
						$product = wc_get_product( $post->ID );
						if ( $product instanceof WC_Product ) {
							$not_allowed_product_types = array( 'grouped' );
							if ( apply_filters( 'yith_wapo_allowed_product_types', true, $not_allowed_product_types ) && in_array( $product->get_type(), $not_allowed_product_types, true ) ) {
								return $product_data_tabs;
							}
						}
					}

					$product_data_tabs['wapo-product-options'] = array(
						'label'  => __( 'Product Add-Ons', 'yith-woocommerce-product-add-ons' ),
						'target' => 'my_custom_product_data',
						'class'  => array( 'yith_wapo_tab_class' ),
					);

					return $product_data_tabs;
				}
			}

			add_action( 'woocommerce_product_data_panels', 'wapo_product_data_fields' );
			if ( ! function_exists( 'wapo_product_data_fields' ) ) {
				/**
				 * WooCommerce Product Data Panels action
				 */
				function wapo_product_data_fields() {
					global $woocommerce, $post, $wpdb; ?>
					<div id="my_custom_product_data" class="panel woocommerce_options_panel">
						<div class="options_group">
							<?php
							woocommerce_wp_checkbox(
								array(
									'id'            => '_wapo_disable_global',
									'wrapper_class' => 'wapo-disable-global',
									'label'         => __( 'Disable Globals', 'yith-woocommerce-product-add-ons' ),
									'description'   => __( 'Check this box if you want to disable global groups and use only the above ones!', 'yith-woocommerce-product-add-ons' ),
									'default'       => '0',
									'desc_tip'      => false,
								)
							);
							?>
						</div>
					</div>
					<?php
				}
			}

		}

		/**
		 * Add Custom General Fields Save
		 *
		 * @param int $post_id Post ID.
		 * @return void
		 */
		public static function woo_add_custom_general_fields_save( $post_id ) {
			$woocommerce_checkbox = isset( $_POST['_wapo_disable_global'] ) ? 'yes' : 'no'; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_post_meta( $post_id, '_wapo_disable_global', $woocommerce_checkbox );
		}

	}
}

/**
 * Unique access to instance of YITH_WAPO_Admin class
 *
 * @return YITH_WAPO_Admin
 */
function YITH_WAPO_Admin() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return YITH_WAPO_Admin::get_instance();
}
